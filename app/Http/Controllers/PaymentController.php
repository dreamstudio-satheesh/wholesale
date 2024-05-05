<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Sale;
use App\Models\PaymentSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function __construct()
    {
       // $this->middleware('auth');
    }

   
    public function salespayment(Request $request)
    {
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'sales_id' => 'required|exists:sales,id',
            'payment_date' => 'required|date',
            'payment_choice_id' => 'required|exists:payment_methods,id',
            'paying_amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Wrap in transaction for data integrity
        DB::transaction(function () use ($request) {
            $paymentSale = new PaymentSale();
            $paymentSale->sale_id = $request->sales_id;
            $paymentSale->user_id = auth()->user()->id;
            $paymentSale->amount = $request->paying_amount;
            $paymentSale->date = $request->payment_date;
            $paymentSale->payment_method_id = $request->payment_choice_id;
            $paymentSale->payment_notes = $request->payment_notes;
            $paymentSale->notes = $request->sales_notes;
            $paymentSale->save();

            // Add additional logic here if necessary, like updating sale status, etc.
        });

        // Update the sale status to 'paid'
        $sale = Sale::find($request->sales_id);
        if ($sale) {
            // Update the paid amount
            $sale->paid_amount += $request->paying_amount;

            // Determine and update the payment status based on the paid amount
            if ($sale->paid_amount >= $sale->grand_total) {
                $sale->payment_status = 'Paid';
            } elseif ($sale->paid_amount > 0 && $sale->paid_amount < $sale->grand_total) {
                $sale->payment_status = 'Partial';
            } else {
                $sale->payment_status = 'UNPaid';
            }

            $sale->save();
        } 

        return response()->json(
            [
                'message' => 'Payment processed successfully',
                // Add any additional response data you need
            ],
            200,
        );
    }
}
