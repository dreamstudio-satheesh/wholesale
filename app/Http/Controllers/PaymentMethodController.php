<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage_payment_methods');
    }

    public function index(): View
    {
        $paymentMethods = PaymentMethod::orderBy('id', 'desc')->get();
        return view('payment_methods.index', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:192',
            // Add other fields as necessary
        ]);

        $paymentMethod = PaymentMethod::create($validatedData);

        return redirect()
            ->route('payment_method.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function update(Request $request, PaymentMethod $payment_method)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:192',
            // Add other fields as necessary
        ]);

        $payment_method->update($validatedData);

        return redirect()
            ->route('payment_method.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $payment_method)
    {
        $payment_method->delete();

        return redirect()
            ->route('payment_method.index')
            ->with('success', 'Payment method deleted successfully.');
    }
}
