<?php

namespace App\Livewire\PurchaseReturn;

use App\Models\PurchaseReturn;
use Livewire\Component;
use App\Models\PurchaseReturnPayment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;

class ShowPaymentModal extends Component
{
    public $showPaymentModal = false;
    public $purchaseId;
    public $payments;

    // edit
    public $paymentMethods = [];
    public $editingPayment = false;
    public $editablePaymentId = null;
    public $editablePaymentData = [];

    protected $listeners = ['showModal' => 'prepareModal'];

    public function prepareModal($purchaseId)
    {
        $this->purchaseId = $purchaseId;
        $this->payments = PurchaseReturnPayment::where('purchase_return_id', $purchaseId)->get();
        $this->showPaymentModal = true;
    }

    public function startEditingPayment($paymentId)
    {
        $this->editingPayment = true;
        $this->editablePaymentId = $paymentId;
        $this->paymentMethods = PaymentMethod::all();
        $payment = PurchaseReturnPayment::findOrFail($paymentId);

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
            $payment = PurchaseReturnPayment::find($this->editablePaymentId);
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

            // Fetch the purchase record once
            $purchase = PurchaseReturn::with('payments')->find($this->purchaseId);
            
            if (!$purchase) {
                // Optionally handle the case where the purchase doesn't exist
                return;
            }

            $totalPayments = $purchase->payments->sum('amount');

            // Update the purchase's payment status based on the new total payments
            if ($totalPayments >= $purchase->grand_total) {
                $purchase->payment_status = 'Paid';
            } else {
                $purchase->payment_status = 'Partial';
            }

           // $purchase->paid_amount +=$this->paymentAmount;

            $purchase->save();

            $this->dispatch('show-toastr', ['type' => 'success', 'message' => 'Payment updated successfully.']);

            $this->stopEditingPayment(); // Reset editing state and optionally close the modal

            $this->showPaymentModal = false;
        });
    }

    public function deletePayment($paymentId)
    {
        DB::transaction(function () use ($paymentId) {
            $payment = PurchaseReturnPayment::find($paymentId);
            if ($payment) {
                $purchaseId = $payment->sale_id; // Store sale_id before deleting the payment
                $payment->delete();

                // After deletion, recalculate the total payments for the purchase
                $purchase = PurchaseReturn::with('payments')->find($purchaseId);
                if ($purchase) {
                    $totalPayments = $purchase->payments->sum('amount');

                    // Update the purchase's payment status based on the new total payments
                    if ($totalPayments >= $purchase->grand_total) {
                        $purchase->payment_status = 'Paid';
                    } elseif ($totalPayments > 0) {
                        $purchase->payment_status = 'Partial';
                    } else {
                        $purchase->payment_status = 'Unpaid'; // Or whatever status you use for no payments
                    }

                    $purchase->save();
                }

                $this->dispatch('show-toastr', ['type' => 'success', 'message' => 'Payment deleted successfully.']);
                $this->showPaymentModal = false;

                $this->dispatch('paymentSuccessful');

                // Optionally, refresh the list of payments to reflect the deletion
                // $this->payments = PurchaseReturnPayment::where('sale_id', $purchaseId)->get();
            }
        });
    }

    public function render()
    {
        return view('livewire.show-payment-modal');
    }
}
