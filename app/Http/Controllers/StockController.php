<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\Transfer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\TransfersItem;
use Illuminate\Support\Facades\DB;
use App\Services\ModuleStatusService;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkModule:stocks');
        $this->middleware('can:manage_stocks')->only('index');
        $this->middleware('can:create_stocks')->only('create');
    }

    // List all stock items
    public function index()
    {
        return view('stocks.index');
    }

   
    public function create()
    {
        return view('stocks.create');
    }

    public function history()
    {
        return view('stocks.history');
    }


    public function transfers()
    {
        return view('stocks.transfers');
    }


    public function create_transfers()
    {
        $customers = Customer::select('id', 'name', 'phone')->get();
        $warehouses = Warehouse::all();
        return view('stocks.create_transfer', compact('customers', 'warehouses'));
    }


    public function createTransfer(Request $request)
    {
        // Define validation rules
        $rules = [
            'transfer_date' => 'required|date',
            'cart' => 'required|array',
            'cart.*.productId' => 'required|integer|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.productPrice' => 'required|numeric|min:0',
            // ... add other validation rules as needed ...
        ];

        // Define custom validation messages (optional)
        $messages = [
            'transfer_date.required' => 'The transfer date field is required.',
            'cart.required' => 'The cart cannot be empty.',
            // ... other custom messages ...
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Start Transaction
        DB::beginTransaction();

        try {
            // Retrieve cart items and other data from the request
            $cartItems = $request->input('cart', []);
            $toWarehouseId = $request->input('warehouseto_id');
            $transferDate = $request->input('transfer_date'); // Ensure the date is in the correct format
            $discount = (float) $request->input('discount');
            $discountType = $request->input('discount_type');
            $taxRate = $request->input('tax_rate');
            $warehouseId = $request->input('warehouse_id');
            $totalAmount = (float) $request->input('total_amount');
            $taxAmount = (float) $request->input('tax_amount');
            $shippingAmount = $request->input('shipping_amount');
            // Perform necessary validations here (e.g., ensure cartItems is not empty)

         

            // Create the sale record
            $transfer = Transfer::create([
                'user_id' => auth()->id(),
                'date' => $transferDate,
                'from_warehouse_id' => $warehouseId ?: 1,
                'to_warehouse_id' => $toWarehouseId ?: 1,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_type' => $discountType,
                'discount' => $discount,
                'grand_total' => $totalAmount,
                'paid_amount' => 0,
                'payment_status' => 'Unpaid',
                'status' => 'new',
                'notes' => '',
            ]);

            // Iterate over each cart item and create corresponding sales items
            foreach ($cartItems as $item) {
                $subtotal = number_format($item['quantity'] * $item['productPrice'], 2, '.', '');
                // Make sure to validate and sanitize $item details
                TransfersItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'price' => $item['productPrice'],
                    'variant_id' => $item['variantId'],
                    'subtotal' => $subtotal,
                ]);

                // If stock handling is enabled, update stock and create transaction record
                if ($this->checkModuleStatus('stocks')) {
                    Stock::create([
                        'product_id' => $item['productId'],
                        'variant_id' => $item['variantId'],
                        'warehouse_id' => $request->input('warehouse_id', 1),
                        'date' => now(),
                        'quantity' => -$item['quantity'],
                        'type' => 'Subtraction',
                        'movement_reason' => 'Transfer Out', // Assuming 'type' field indicates stock movement direction
                        'related_order_id' => $transfer->id,
                        'created_by' => auth()->id(),
                        // ... other stock fields ...
                    ]);


                    Stock::create([
                        'product_id' => $item['productId'],
                        'variant_id' => $item['variantId'],
                        'warehouse_id' => $request->input('warehouseto_id', 1),
                        'date' => now(),
                        'quantity' => $item['quantity'],
                        'type' => 'Addition',
                        'movement_reason' => 'Transfer In', // Assuming 'type' field indicates stock movement direction
                        'related_order_id' => $transfer->id,
                        'created_by' => auth()->id(),
                        // ... other stock fields ...
                    ]);


                   
                }
            }

          

            // Commit Transaction
            DB::commit();

            // Return success response
            return response()->json(
                [
                    'message' => 'Transfer successfully added',
                    'grandTotal' => $transfer->grand_total,
                    'transfersId' => $transfer->id, // Include the sales ID
                ],
                200,
            );
        } catch (Exception $e) {
            // Rollback Transaction in case of error
            DB::rollBack();

            // Log the exception
            Log::error($e);

            // Return error response
            return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }


    protected function checkModuleStatus($module)
    {
        $moduleStatuses = ModuleStatusService::getModuleStatuses();
        return $moduleStatuses[$module] ?? false;
    }



    
}
