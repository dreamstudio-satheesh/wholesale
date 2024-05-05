<?php

namespace App\Http\Traits;

use Log;
use App\Models\Stock;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use App\Models\PurchasesItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use Illuminate\Support\Facades\DB;
use App\Services\ModuleStatusService;
use Illuminate\Support\Facades\Validator;

trait HandlesPurchaseOperations
{
    public function createPurchase(Request $request)
    {
        // Define validation rules
        $rules = [
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'cart' => 'required|array',
            'cart.*.productId' => 'required|integer|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
            // Add other validation rules as needed...
        ];

        // Define custom validation messages (optional)
        $messages = [
            'supplier_id.required' => 'The supplier field is required.',
            'purchase_date.required' => 'The purchase date field is required.',
            'cart.required' => 'The cart cannot be empty.',
            // Other custom messages...
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Start Transaction
        DB::beginTransaction();

        try {
            // Retrieve data from the request
            $cartItems = $request->input('cart');
            $supplierId = $request->input('supplier_id');
            $purchaseDate = $request->input('purchase_date');
            $warehouseId = $request->input('warehouse_id', 1); // Default to warehouse ID 1 if not provided
            $discount = (float) $request->input('discount_amount');
            $discountType = $request->input('discount_type');
            $taxRate = $request->input('tax_rate');
            $totalAmount = (float) $request->input('total_amount');
            $taxAmount = (float) $request->input('tax_amount');
            $shippingAmount = $request->input('shipping_amount');

            // Create the purchase record
            $purchase = Purchase::create([
                'user_id' => auth()->id(),
                'supplier_id' => $supplierId,
                'warehouse_id' => $warehouseId,
                'date' => $purchaseDate,
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
                // Add additional fields as necessary...
            ]);

            // Iterate over each cart item and create corresponding purchase items
            foreach ($cartItems as $item) {
                $subtotal = number_format($item['quantity'] * $item['price'], 2, '.', '');
                PurchasesItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'variant_id' => $item['variantId'],
                    'subtotal' => $subtotal,
                    // Additional fields as needed...
                ]);

                if ($this->checkModuleStatus('stocks')) {
                    // Update stock and create transaction record if necessary...
                    Stock::create([
                        'product_id' => $item['productId'],
                        'warehouse_id' => $warehouseId,
                        'quantity' => $item['quantity'],
                        'type' => 'Addition', // Assuming 'type' field indicates stock movement direction
                        'movement_reason' => 'Purchase', // Assuming 'type' field indicates stock movement direction
                        'date' => now(),
                        'related_order_id' => $purchase->id,
                        'created_by' => auth()->id(),
                        // Additional fields as needed...
                    ]);

                    // Record the transaction for stock movement
                    Transaction::create([
                        'product_id' => $item['productId'],
                        'warehouse_id' => $warehouseId,
                        'type' => 'purchase',
                        'quantity' => $item['quantity'],
                        'transaction_date' => now(),
                        'related_order_id' => $purchase->id,
                        'created_by' => auth()->id(),
                        // Additional fields as needed...
                    ]);
                }
            }

            // Update any related settings or perform additional operations as needed...

            // Commit Transaction
            DB::commit();

            // Return success response
            return response()->json(
                [
                    'message' => 'Purchase successfully added',
                    'purchaseId' => $purchase->id, // Include the purchase ID for reference
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

    public function editPurchase(Request $request, $purchaseId)
    {
        // Define validation rules
        $rules = [
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'purchase_date' => 'required|date',
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

        $cartItems = $request->input('cart', []);
        $purchaseDate = $request->input('purchase_date'); // Ensure the date is in the correct format
        $warehouseId = $request->input('warehouse_id', 1);
        $discount = (float) $request->input('discount');
        $discountType = $request->input('discount_type');
        $taxRate = $request->input('tax_rate');
        $totalAmount = (float) $request->input('total_amount');
        $taxAmount = (float) $request->input('tax_amount');
        $shippingAmount = $request->input('shipping_amount');

        // Start Transaction
        DB::beginTransaction();

        try {
            // Fetch the existing Purchase record
            $purchase = Purchase::findOrFail($purchaseId);

            // Update the Purchase record with new values
            $data = [
                'user_id' => auth()->id(),
                'date' => $purchaseDate,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_type' => $discountType,
                'discount' => $discount,
                'grand_total' => $totalAmount,
                'status' => 'updated',
            ];

            // Update other Purchase fields as necessary...

            $purchase->update($data);

            // First, delete related Transactions and Stocks for each item
            foreach ($purchase->items as $item) {
                Transaction::where('product_id', $item->id)
                    ->where('type', 'purchase')
                    ->delete();
            }

            

            // Remove existing PurchasesItem records (or update them if you prefer)
            $purchase->items()->delete();

            // Iterate over each cart item and create new PurchasesItem records
            $cartItems = $request->input('cart', []);
            foreach ($cartItems as $item) {
                $subtotal = number_format($item['quantity'] * $item['productPrice'], 2, '.', '');
                PurchasesItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'price' => $item['productPrice'],
                    'variant_id' => $item['variantId'],
                    'subtotal' => $subtotal,
                    // Include variant_id and other fields as necessary...
                ]);


                if ($this->checkModuleStatus('stocks')) {
                    Stock::where('related_order_id', $purchase->id)
                        ->where('type', 'Addition')
                        ->delete();
    
                    Stock::create([
                        'product_id' => $item['productId'],
                        'warehouse_id' => $warehouseId,
                        'quantity' => $item['quantity'],
                        'type' => 'Addition', // Assuming 'type' field indicates stock movement direction
                        'movement_reason' => 'Purchase', // Assuming 'type' field indicates stock movement direction
                        'date' => now(),
                        'related_order_id' => $purchase->id,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // Commit Transaction
            DB::commit();

            // Return success response
            return response()->json(['message' => 'Purchase updated successfully', 'purchaseId' => $purchase->id], 200);
        } catch (Exception $e) {
            // Rollback Transaction in case of error
            DB::rollBack();

            // Log the exception and return an error response
            Log::error($e);
            return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function storePurchasesReturn(Request $request, $purchaseId)
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

        // Check if purchase returns already exist for this purchase
        $purchaseReturnsExist = PurchaseReturn::where('purchase_id', $purchaseId)->exists();

        if ($purchaseReturnsExist) {
            // Return an error message if a purchase return already exists for the purchaseId
            return response()->json(['errors' => 'A return for this purchase already exists.'], 409); // 409 Conflict or another suitable status code
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
            $purchase = Purchase::findOrFail($purchaseId);

            $purchaseReturn = new PurchaseReturn([
                'purchase_id' => $purchase->id,
                'user_id' => auth()->id(),
                'date' => $request->input('return_date'),
                'return_invoice_number' => 'SR' . time(), // Example invoice number
                // Add other fields as necessary
                'supplier_id' => $purchase->supplier_id,
                'warehouse_id' => $purchase->warehouse_id ?: 1,
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
            $purchaseReturn->save();

            foreach ($request->input('return_cart') as $item) {
                $subtotal = number_format($item['quantity'] * $item['price'], 2, '.', '');
                PurchaseReturnItem::create([
                    'purchase_return_id' => $purchaseReturn->id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Purchases return processed successfully', 'salesReturnId' => $purchaseReturn->id], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function updatePurchasesReturn(Request $request, $purchaseId)
    {
        $rules = [
            'purchase_return_id' => 'required',
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

        // Check if purchase returns already exist for this purchase
        $purchaseReturn = PurchaseReturn::where('id', $request->input('purchase_return_id'))->first();

        if ($purchaseReturn) {
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
                $purchase = Purchase::findOrFail($purchaseId);

                $data = [
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
                $purchaseReturn->update($data);
                PurchaseReturnItem::where('purchase_return_id', $purchaseReturn->id)->delete();

                foreach ($request->input('return_cart') as $item) {
                    $subtotal = number_format($item['quantity'] * $item['price'], 2, '.', '');
                    PurchaseReturnItem::create([
                        'purchase_return_id' => $purchaseReturn->id,
                        'product_id' => $item['productId'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $subtotal,
                    ]);
                }

                DB::commit();
                return response()->json(['message' => 'Purchases return updated successfully', 'purchaseReturnId' => $purchaseReturn->id], 200);
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
            }
        } else {
            // Return an error message if a purchase return already exists for the purchaseId
            return response()->json(['errors' => 'A return for this purchase not exists.'], 409); // 409 Conflict or another suitable status code
        }
    }

    // Check if stock module is enabled (modify this method according to your application's logic)
    protected function checkModuleStatus($module)
    {
        $moduleStatuses = ModuleStatusService::getModuleStatuses();
        return $moduleStatuses[$module] ?? false;
    }

    // Include methods for editing purchases, updating stocks, etc., similar to HandlesPurchasesOperations
}
