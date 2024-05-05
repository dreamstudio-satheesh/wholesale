<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Sale;
use App\Models\Account;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Traits\HandlesSalesOperations;

class PosController extends Controller
{
    use HandlesSalesOperations;
    public function __construct()
    {
        $this->middleware(['checkModule:pos', 'can:show_pos']);
    }

    public function getitems(Request $request)
    {
        $categoryId = $request->input('category_id');

        $warehouseId = $request->input('warehouse_id',1);

        $query = Product::query()->with(['variants', 'unit']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $paginator = $query->paginate(8);

        if ($categoryId) {
            $paginator->appends(['category_id' => $categoryId]);
        }
        if ($warehouseId) {
            // Append warehouse_id to pagination links
            $paginator->appends(['warehouse_id' => $warehouseId]);
        }

        $productslist = $paginator->getCollection();

        $transformedProducts = collect();

        foreach ($productslist as $product) {

            if ($product->getMedia('products')->isEmpty()){

                $imageUrl =url('image/no-image.webp');
                
            }else{
                $imageUrl = $product->getMedia("products")[0]->getUrl('preview');
            }
           

            if ($product->product_type === 'variable' && $product->variants->isNotEmpty()) {
                foreach ($product->variants as $variant) {
                    $variantProduct = clone $product; // Clone the product to retain base properties

                    // Update product name and price based on variant
                    $variantProduct->name = "{$product->name} - {$variant->name}";
                    $variantProduct->price = $variant->price; // Assuming the variant has a price field

                    // Add variant_id and remove variants array
                    $variantProduct->variant_id = $variant->id; // Assuming the variant has an id field
                    $variantProduct->current_stock = $this->calculateCurrentStock($product->id, $variant->id, $warehouseId);

                    unset($variantProduct->variants); // Remove the variants array

                    // Add variant info
                    $variantProduct->variant_info = $variant;

                    $variantProduct->image = $imageUrl;

                    $transformedProducts->push($variantProduct);
                }
            } else {
                $product->variant_id = null;
                $product->current_stock = $this->calculateCurrentStock($product->id, null, $warehouseId);

                $product->image = $imageUrl;

                $transformedProducts->push($product); // Push the product itself if it's not a variant type
            }
        }

        // Replace the original items in the paginator with the transformed products
        $paginator->setCollection($transformedProducts);

        // $paginator is a LengthAwarePaginator instance with your custom collection
        // and it still has all the original pagination properties.

        return response()->json(['products' => $paginator]);
    }

   
    public function viewpos($id)
    {
        $invoice = Sale::with(['items.product', 'items.variant'])->withTrashed()->findOrFail($id);
        $subtotal = $invoice->items->sum('subtotal');

        $discount = 0;

        if (!empty($invoice->discount_percent_total) && $invoice->discount_percent_total > 0) {
            $discount = ($subtotalSum * $invoice->discount_percent_total) / 100;
        } elseif (!empty($invoice->discount) && $invoice->discount > 0) {
            $discount = $invoice->discount;
        }
        return view('pos.print', compact('invoice', 'subtotal', 'discount'));
    }

   


    public function index(Request $request)
    {
        $categories = Category::all();
        $warehouses = Warehouse::all();
        $accounts = Account::all();
        $customers = Customer::select('id', 'name', 'phone')->get();
        $paymentMethods = PaymentMethod::all();

        return view('pos.index', compact('categories', 'customers', 'warehouses', 'paymentMethods', 'accounts'));
    }
}
