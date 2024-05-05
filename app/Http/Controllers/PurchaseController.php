<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PurchasesExport;
use App\Exports\PurchasesExportPDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchasesReturnExport;
use App\Exports\PurchasesReturnExportPDF;
use App\Http\Traits\HandlesPurchaseOperations;

class PurchaseController extends Controller
{
    use HandlesPurchaseOperations;

    public function __construct()
    {
        $this->middleware('can:view_purchases')->only('index');
        $this->middleware('can:create_purchases')->only(['create', 'createPurchase']);
        $this->middleware('can:edit_purchases')->only(['edit', 'editPurchase']);
        // $this->middleware('can:delete_purchases')->only('destroy');
    }

    public function index()
    {
        return view('purchases.index');
    }

    public function show($id)
    {
        $purchase = Purchase::with(['items.product', 'supplier', 'warehouse'])->find($id);
        if ($purchase) {
            return view('purchases.show', compact('purchase'));
        }
        abort(404);
    }

    public function return_show($id)
    {
        $purchasesreturn = PurchaseReturn::with(['items.product', 'supplier', 'warehouse'])->find($id);
        if ($purchasesreturn) {
            return view('purchases.show-return', compact('purchasesreturn'));
        }
        abort(404);
    }

    public function return_list()
    {
        return view('purchases.returnlist');
    }

    public function create_return(Purchase $purchase)
    {
        $purchases = $purchase->load('items.product');

        foreach ($purchases->items as $item) {
            $warehouse_id = $purchase->warehouse_id;
            $variant_id = $item->variant_id ?? null;
        }

        $cartItems = $purchases->items
            ->map(function ($item) use ($purchase) {
                // Concatenate product_id and variant_id (if exists) for a unique identifier
                $productIdentifier = $item->variant_id ? $item->product_id . '-' . $item->variant_id : $item->product_id;
                // Append variant info to product name if variant_id exists
                $productName = $item->variant_id ? $item->product->name . ' - ' . $item->variant->name : $item->product->name;

                return [
                    'productId' => (string) $item->product_id,
                    'productIdentifier' => (string) $productIdentifier,
                    'productName' => $productName,
                    'productPrice' => (float) $item->price,
                    'quantity' => 0,
                    'availableQuantity' => (int) $item->quantity,
                    'variantId' => $item->variant_id ? (string) $item->variant_id : null,
                    'subtotal' => (float) 0.0,
                ];
            })
            ->toJson();

        return view('purchases.create_return', compact('purchases', 'cartItems'));
    }

    public function edit_return($id)
    {
        $purchasereturn = PurchaseReturn::find($id);

        if (!$purchasereturn) {
            // Handle the case where PurchaseReturn is not found
            return back()->withErrors(['msg' => 'Purchase Return not found.']);
        }

        $purchases = Purchase::find($purchasereturn->purchase_id);
        if (!$purchases) {
            // Handle the case where Purchase is not found
            return back()->withErrors(['msg' => 'Purchase not found.']);
        }

        $purchases->load('items.product');

        // Assuming purchasereturn->items is available and has a similar structure to purchase->items
        // This map will help us quickly find a returned quantity for a given product and variant
        $returnedItemsMap = $purchasereturn->items->mapWithKeys(function ($item) {
            $key = $item->variant_id ? $item->product_id . '-' . $item->variant_id : $item->product_id;
            return [$key => $item->quantity]; // Assuming each item has a 'quantity' attribute
        });

        $cartItems = $purchases->items
            ->map(function ($item) use ($returnedItemsMap) {
                $productIdentifier = $item->variant_id ? $item->product_id . '-' . $item->variant_id : $item->product_id;
                $productName = $item->variant_id ? $item->product->name . ' - ' . $item->variant->name : $item->product->name;

                // Fetch the returned quantity from the map, defaulting to 0 if not found
                $returnedQuantity = $returnedItemsMap->get($productIdentifier, 0);

                return [
                    'productId' => (string) $item->product_id,
                    'productIdentifier' => (string) $productIdentifier,
                    'productName' => $productName,
                    'productPrice' => (float) $item->price,
                    'quantity' => (int) $returnedQuantity, // Use the returned quantity here
                    'availableQuantity' => (int) $item->quantity,
                    'variantId' => $item->variant_id ? (string) $item->variant_id : null,
                    'subtotal' => (float) ($item->price * $returnedQuantity), // Calculate subtotal based on returned quantity
                ];
            })
            ->toJson();

        return view('purchases.edit_return', compact('purchases', 'cartItems','purchasereturn'));
    }

    public function create()
    {
        $suppliers = Supplier::select('id', 'name', 'phone')->get();
        $warehouses = Warehouse::all();
        return view('purchases.create', compact('suppliers', 'warehouses'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $warehouse_id = $request->input('warehouse_id', 1);

        $products = Product::with(['variants'])
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('sku', 'like', $query . '%')
            ->get();

        $transformedProducts = collect();
        foreach ($products as $product) {
            if ($product->product_type === 'variable' && $product->variants->isNotEmpty()) {
                foreach ($product->variants as $variant) {
                    $variantProduct = clone $product; // Clone the product to retain base properties

                    // Update product name and price based on variant
                    $variantProduct->name = "{$product->name} - {$variant->name}";
                    $variantProduct->price = $variant->price;
                    $variantProduct->cost = $variant->cost;
                    // Add variant_id and remove variants array
                    $variantProduct->variant_id = $variant->id;
                    unset($variantProduct->variants); // Remove the variants array

                    // Add variant info and current stock
                    $variantProduct->variant_info = $variant;

                    $transformedProducts->push($variantProduct);
                }
            } else {
                // Set variant_id to null for non-variable products
                $product->variant_id = null;
                $transformedProducts->push($product); // Push the product itself if it's not a variant type
            }
            // Append the current_stock to each product
        }

        return response()->json($transformedProducts);
    }

    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::select('id', 'name', 'phone')->get();
        $warehouses = Warehouse::all();
        $purchases = $purchase->load('items.product');

        foreach ($purchases->items as $item) {
            $warehouse_id = $purchase->warehouse_id;
            $variant_id = $item->variant_id ?? null;
        }

        $cartItems = $purchases->items
            ->map(function ($item) use ($purchase) {
                // Concatenate product_id and variant_id (if exists) for a unique identifier
                $productIdentifier = $item->variant_id ? $item->product_id . '-' . $item->variant_id : $item->product_id;
                // Append variant info to product name if variant_id exists
                $productName = $item->variant_id ? $item->product->name . ' - ' . $item->variant->name : $item->product->name;

                return [
                    'productId' => (string) $item->product_id,
                    'productIdentifier' => (string) $productIdentifier,
                    'productName' => $productName,
                    'productPrice' => (float) $item->price,
                    'quantity' => (int) $item->quantity,
                    'variantId' => $item->variant_id ? (string) $item->variant_id : null,
                    'subtotal' => (float) ($item->quantity * $item->price),
                ];
            })
            ->toJson();

        // return $purchases;
        return view('purchases.edit', compact('suppliers', 'warehouses', 'purchases', 'cartItems'));
    }

    public function restore($id)
    {
        $purchase = Purchase::withTrashed()->findOrFail($id);
        $purchase->restore();
        return redirect()->route('purchases.index')->with('success', 'Purchase restored successfully.');
    }

    public function export($type)
    {
        if ($type == 'excel') {
            return Excel::download(new PurchasesExport(), 'purchases.xlsx');
        } elseif ($type == 'csv') {
            return Excel::download(new PurchasesExport(), 'purchases.csv', \Maatwebsite\Excel\Excel::CSV);
        } elseif ($type == 'pdf') {
            return Excel::download(new PurchasesExportPDF(), 'purchases.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }
    }

    public function export_return($type)
    {
        if ($type == 'excel') {
            return Excel::download(new PurchasesReturnExport(), 'purchases_return.xlsx');
        } elseif ($type == 'csv') {
            return Excel::download(new PurchasesReturnExport(), 'purchases_return.csv', \Maatwebsite\Excel\Excel::CSV);
        } elseif ($type == 'pdf') {
            return Excel::download(new PurchasesReturnExportPDF(), 'purchases_return.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }
    }

    public function download($invoiceId)
    {
        $purchase = Purchase::with(['items.product', 'supplier', 'warehouse'])->find($invoiceId);
        // Ensure you have the invoice. Redirect or abort if not found.

        //Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $pdf = PDF::loadView('pdf.purchase', compact('purchase'));

        return $pdf->download('bill-' . $invoiceId . '.pdf');
    }

    public function download_return($invoiceId)
    {
        $purchase = PurchaseReturn::with(['items.product', 'supplier', 'warehouse'])->find($invoiceId);
        // Ensure you have the invoice. Redirect or abort if not found.

        //Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $pdf = PDF::loadView('pdf.purchase', compact('purchase'));

        return $pdf->download('bill-' . $invoiceId . '.pdf');
    }
}
