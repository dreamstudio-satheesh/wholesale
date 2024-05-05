<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use App\Models\PaymentSale;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;

class ShowPaymentModal extends Component
{
    public $showPaymentModal = false;
    public $saleId;
    public $payments;

    // edit
    public $paymentMethods = [];
    public $editingPayment = false;
    public $editablePaymentId = null;
    public $editablePaymentData = [];

    protected $listeners = ['showModal' => 'prepareModal'];

    public function prepareModal($saleId)
    {
        $this->saleId = $saleId;
        $this->payments = PaymentSale::where('sale_id', $saleId)->get();
        $this->showPaymentModal = true;
    }

    public function startEditingPayment($paymentId)
    {
        $this->editingPayment = true;
        $this->editablePaymentId = $paymentId;
        $this->paymentMethods = PaymentMethod::all();
        $payment = PaymentSale::findOrFail($paymentId);

        $this->editablePaymentData = [
            'id' => $payment->id,
            'paymentDate' => $payment->date ? $payment->date->format('Y-m-d\TH:i') : null,
            'paymentChoice' => $payment->payment_method_id,
            'amount' => $payment->amount,
            'paymentNotes' => $payment->payment_notes,
        ];
    }

    public function stopEditingPayment()
    {
        $this->reset(['editingPayment', 'editablePaymentId', 'editablePaymentData']);

        // Optionally, reload payments or close the modal
        $this->editingPayment = false;

        $this->dispatch('paymentSuccessful');
    }

    public function savePayment()
    {
        $this->validate([
            'editablePaymentData.amount' => 'required|numeric',
            'editablePaymentData.paymentDate' => 'required|date',
            'editablePaymentData.paymentChoice' => 'required|exists:payment_methods,id', // Assuming your table name and primary key
            // Add other validation rules as necessary
        ]);

        DB::transaction(function () {
            $payment = PaymentSale::find($this->editablePaymentId);
            if ($payment) {
                $payment->update([
                    'amount' => $this->editablePaymentData['amount'],
                    'date' => $this->editablePaymentData['paymentDate'],
                    'payment_method_id' => $this->editablePaymentData['paymentChoice'],
                    'payment_notes' => $this->editablePaymentData['paymentNotes'],
                    'user_id' => auth()->id(),
                ]);
                $payment->update($this->editablePaymentData);
            }

            // Fetch the sale record once
            $sale = Sale::with('payments')->find($this->saleId);

            if (!$sale) {
                // Optionally handle the case where the sale doesn't exist
                return;
            }

            $totalPayments = $sale->payments->sum('amount');

            // Update the sale's payment status based on the new total payments
            if ($totalPayments >= $sale->grand_total) {
                $sale->payment_status = 'Paid';
            } else {
                $sale->payment_status = 'Partial';
            }

            $sale->save();

            $this->dispatch('show-toastr', ['type' => 'success', 'message' => 'Payment updated successfully.']);

            $this->stopEditingPayment(); // Reset editing state and optionally close the modal
            $this->showPaymentModal = false;
        });
    }

    public function deletePayment($paymentId)
    {
        DB::transaction(function () use ($paymentId) {
            $payment = PaymentSale::find($paymentId);
            if ($payment) {
                $saleId = $payment->sale_id; // Store sale_id before deleting the payment
                $payment->delete();

                // After deletion, recalculate the total payments for the sale
                $sale = Sale::with('payments')->find($saleId);
                if ($sale) {
                    $totalPayments = $sale->payments->sum('amount');

                    // Update the sale's payment status based on the new total payments
                    if ($totalPayments >= $sale->grand_total) {
                        $sale->payment_status = 'Paid';
                    } elseif ($totalPayments > 0) {
                        $sale->payment_status = 'Partial';
                    } else {
                        $sale->payment_status = 'Unpaid'; // Or whatever status you use for no payments
                    }

                    $sale->save();
                }

                $this->dispatch('show-toastr', ['type' => 'success', 'message' => 'Payment deleted successfully.']);
                $this->showPaymentModal = false;

                $this->dispatch('paymentSuccessful');

                // Optionally, refresh the list of payments to reflect the deletion
                // $this->payments = PaymentSale::where('sale_id', $saleId)->get();
            }
        });
    }

    public function render()
    {
        return view('livewire.show-payment-modal');
    }
}
