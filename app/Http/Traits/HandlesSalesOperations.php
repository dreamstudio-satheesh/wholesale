<?php

namespace App\Http\Traits;

use Log;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\SalesItem;
use App\Models\SalesReturn;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SalesReturnItem;
use Illuminate\Support\Facades\DB;
use App\Services\ModuleStatusService;
use Illuminate\Support\Facades\Validator;

trait HandlesSalesOperations
{
    public function createSale(Request $request)
    {
        // Define validation rules
        $rules = [
            'customer_id' => 'required|integer|exists:customers,id',
            'sale_date' => 'required|date',
            'cart' => 'required|array',
            'cart.*.productId' => 'required|integer|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.productPrice' => 'required|numeric|min:0',
            // ... add other validation rules as needed ...
        ];

        // Define custom validation messages (optional)
        $messages = [
            'customer_id.required' => 'The customer field is required.',
            'sale_date.required' => 'The sale date field is required.',
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
            $customerId = $request->input('customer_id');
            $saleDate = $request->input('sale_date'); // Ensure the date is in the correct format
            $discount = (float) $request->input('discount');
            $discountType = $request->input('discount_type');
            $taxRate = $request->input('tax_rate');
            $warehouseId = $request->input('warehouse_id');
            $totalAmount = (float) $request->input('total_amount');
            $taxAmount = (float) $request->input('tax_amount');
            $shippingAmount = $request->input('shipping_amount');
            // Perform necessary validations here (e.g., ensure cartItems is not empty)

            // Retrieve and Increment the Last Invoice Number
            $prefix = DB::table('settings')->where('key', 'invoice_prefix')->value('value');
            $suffix = DB::table('settings')->where('key', 'invoice_suffix')->value('value');
            $lastNumber = (int) DB::table('settings')->where('key', 'last_invoice_number')->value('value');

            $newInvoiceNumber = $lastNumber + 1;

            $formattedInvoiceNumber = $prefix . $newInvoiceNumber . $suffix;

            // Create the sale record
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'date' => $saleDate,
                'customer_id' => $customerId,
                'invoice_number' => $formattedInvoiceNumber,
                'warehouse_id' => $warehouseId ?: 1,
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
                SalesItem::create([
                    'sale_id' => $sale->id,
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
                        'movement_reason' => 'Sale', // Assuming 'type' field indicates stock movement direction
                        'related_order_id' => $sale->id,
                        'created_by' => auth()->id(),
                        // ... other stock fields ...
                    ]);

                    Transaction::create([
                        'product_id' => $item['productId'],
                        'variant_id' => $item['variantId'],
                        'warehouse_id' => $request->input('warehouse_id', 1),
                        'type' => 'sale',
                        'quantity' => $item['quantity'],
                        'transaction_date' => now(),
                        'created_by' => auth()->id(),
                        // ... other transaction fields ...
                    ]);
                }
            }

            // Update the Last Invoice Number in Settings
            DB::table('settings')
                ->where('key', 'last_invoice_number')
                ->update(['value' => (string) $newInvoiceNumber]);

            // Commit Transaction
            DB::commit();

            // Return success response
            return response()->json(
                [
                    'message' => 'Sale successfully added',
                    'grandTotal' => $sale->grand_total,
                    'salesId' => $sale->id, // Include the sales ID
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

    public function editSale(Request $request, $saleId)
    {
        // Define validation rules
        $rules = [
            'customer_id' => 'required|integer|exists:customers,id',
            'sale_date' => 'required|date',
            'cart' => 'required|array',
            'cart.*.productId' => 'required|integer|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.productPrice' => 'required|numeric|min:0',
            // Add other validation rules as needed...
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Start Transaction
        DB::beginTransaction();

        try {
            // Fetch the existing Sale record
            $sale = Sale::findOrFail($saleId);

            // Update the Sale record with new values
            $sale->customer_id = $request->input('customer_id');
            $sale->date = $request->input('sale_date');
            $sale->grand_total =$request->input('total_amount');
            // Update other Sale fields as necessary...

            $sale->save();

            // First, delete related Transactions and Stocks for each item
            foreach ($sale->items as $item) {
                Transaction::where('product_id', $item->id)
                    ->where('type', 'sale')
                    ->delete();
            }
            Stock::where('related_order_id', $sale->id)
                ->where('type', 'Subtraction')
                ->delete();

            // Remove existing SalesItem records (or update them if you prefer)
            $sale->items()->delete();

            // Iterate over each cart item and create new SalesItem records
            $cartItems = $request->input('cart', []);
            foreach ($cartItems as $item) {
                $subtotal = number_format($item['quantity'] * $item['productPrice'], 2, '.', '');
                SalesItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'price' => $item['productPrice'],
                    'variant_id' => $item['variantId'],
                    'subtotal' => $subtotal,
                    // Include variant_id and other fields as necessary...
                ]);
            }

            // Commit Transaction
            DB::commit();

            // Return success response
            return response()->json(['message' => 'Sale updated successfully', 'saleId' => $sale->id], 200);
        } catch (Exception $e) {
            // Rollback Transaction in case of error
            DB::rollBack();

            // Log the exception and return an error response
            Log::error($e);
            return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function storeSalesReturn(Request $request, $saleId)
    {
        $rules = [
            'return_date' => 'required|date',
            'return_cart' => 'required|array',
            'return_cart.*.productId' => 'required|integer|exists:products,id',
            'return_cart.*.quantity' => 'required|integer|min:0',
            'return_cart.*.price' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if sale returns already exist for this sale
        $saleReturnsExist = SalesReturn::where('sale_id', $saleId)->exists();

        if ($saleReturnsExist) {
            // Return an error message if a sale return already exists for the saleId
            return response()->json(['errors' => 'A return for this sale already exists.'], 409); // 409 Conflict or another suitable status code
        }

        $cartItems = $request->input('return_cart', []);
        $returnDate = $request->input('return_date'); // Ensure the date is in the correct format
        $discount = (float) $request->input('discount');
        $discountType = $request->input('discount_type');
        $taxRate = $request->input('tax_rate');
        $totalAmount = (float) $request->input('total_amount');
        $taxAmount = (float) $request->input('tax_amount');
        $shippingAmount = $request->input('shipping_amount');

        DB::beginTransaction();

        try {
            $sale = Sale::findOrFail($saleId);

            $salesReturn = new SalesReturn([
                'sale_id' => $sale->id,
                'user_id' => auth()->id(),
                'date' => $request->input('return_date'),
                'return_invoice_number' => 'SR' . time(), // Example invoice number
                // Add other fields as necessary
                'customer_id' => $sale->customer_id,
                'warehouse_id' => $sale->warehouse_id ?: 1,
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
            $salesReturn->save();

            foreach ($request->input('return_cart') as $item) {
                $subtotal = number_format($item['quantity'] * $item['price'], 2, '.', '');
                SalesReturnItem::create([
                    'sales_return_id' => $salesReturn->id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Sales return processed successfully', 'salesReturnId' => $salesReturn->id], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function storeEditSalesReturn(Request $request, $saleId)
    {
        $rules = [
            'salesreturn_id' => 'required',
            'return_date' => 'required|date',
            'return_cart' => 'required|array',
            'return_cart.*.productId' => 'required|integer|exists:products,id',
            'return_cart.*.quantity' => 'required|integer|min:0',
            'return_cart.*.price' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if sale returns already exist for this sale
        $saleReturn = SalesReturn::where('id', $request->input('salesreturn_id'))->first();

        if ($saleReturn) {

            $cartItems = $request->input('return_cart', []);
            $returnDate = $request->input('return_date'); // Ensure the date is in the correct format
            $discount = (float) $request->input('discount');
            $discountType = $request->input('discount_type');
            $taxRate = $request->input('tax_rate');
            $totalAmount = (float) $request->input('total_amount');
            $taxAmount = (float) $request->input('tax_amount');
            $shippingAmount = $request->input('shipping_amount');
    
            DB::beginTransaction();
    
            try {
              
                $data =[
                    'user_id' => auth()->id(),
                    'date' => $request->input('return_date'),
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'shipping_amount' => $shippingAmount,
                    'discount_type' => $discountType,
                    'discount' => $discount,
                    'grand_total' => $totalAmount,
                    'status' => 'updated',
                ];
                $saleReturn->update($data);

                SalesReturnItem::where('sales_return_id', $saleReturn->id )->delete();
    
                foreach ($request->input('return_cart') as $item) {
                    $subtotal = number_format($item['quantity'] * $item['price'], 2, '.', '');
                    SalesReturnItem::create([
                        'sales_return_id' => $saleReturn->id,
                        'product_id' => $item['productId'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $subtotal,
                    ]);
                }
    
                DB::commit();
                return response()->json(['message' => 'Sales return updated successfully', 'salesReturnId' => $saleReturn->id], 200);
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
            }
            
        }
        else{
            // Return an error message if a sale return already exists for the saleId
            return response()->json(['errors' => 'Sales Return Not  exists.'], 409); // 409 Conflict or another suitable status code
        }

       
    }


    // Check if stock module is enabled (modify this method according to your application's logic)
    protected function checkModuleStatus($module)
    {
        $moduleStatuses = ModuleStatusService::getModuleStatuses();
        return $moduleStatuses[$module] ?? false;
    }

    private function calculateCurrentStock($productId, $variantId, $warehouseId = 1)
    {
        $query = Stock::query();
        $query->where('product_id', $productId);

        if (is_null($variantId)) {
            $query->whereNull('variant_id');
        } else {
            $query->where('variant_id', $variantId);
        }

        if (!is_null($warehouseId)) {
            $query->where('warehouse_id', $warehouseId);
        }
        return $currentStock = $query->sum('quantity');
    }

    // Include any other methods related to sales operations if necessary
}
