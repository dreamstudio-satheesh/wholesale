<?php

namespace App\Livewire\Purchase;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PaymentMethod;
use App\Models\PaymentPurchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreatePaymentModal extends Component
{
    public $showCreatePaymentModal = false;
    public $purchaseId;
    public $paymentDate;
    public $paymentChoice;
    public $paymentAmount;
    public $paymentNotes;
    public $paymentMethods = [];

    protected $listeners = ['openModal' => 'prepareModal'];

    public function prepareModal($purchaseId)
    {
        $this->purchaseId = $purchaseId;
        $this->resetInputFields();
        $this->loadPendingPaymentAmount($purchaseId);
        $this->showCreatePaymentModal = true;
    }

    protected function loadPendingPaymentAmount($purchaseId)
    {
        $purchase = Purchase::find($purchaseId);
        if ($purchase) {
            $this->paymentAmount = $purchase->pendingPaymentAmount();
        }
    }

    public function resetInputFields()
    {
        $this->paymentDate = now()->format('Y-m-d\TH:i');
        $this->paymentChoice = '';
        $this->paymentAmount = 0;
        $this->paymentNotes = '';
    }

    public function createPayment()
    {
        // Validate input fields
        $this->validate([
            'paymentDate' => 'required|date',
            'paymentChoice' => 'required',
            'paymentAmount' => 'required|numeric',
            'paymentNotes' => 'nullable|string',
        ]);

        // Logic to create a payment record in the database

        DB::transaction(function () {
            // Fetch the purchase record once
            $purchase = Purchase::with('payments')->find($this->purchaseId);
        
            if (!$purchase) {
                // Optionally handle the case where the purchase doesn't exist
                return;
            }

       
        
            // Create the new payment purchase record
            $paymentSale = new PaymentPurchase([
                'purchase_id' => $purchase->id,
                'user_id' => Auth::user()->id,
                'amount' => $this->paymentAmount,
                'date' => $this->paymentDate,
                'payment_method_id' => $this->paymentChoice,
                'payment_notes' => $this->paymentNotes,
                'notes' => '', // Assuming you want to capture additional sales-related notes
            ]);
            $paymentSale->save();

            $totalPayments = $purchase->payments->sum('amount') + $this->paymentAmount;
        
            // Update the purchase's payment status based on the new total payments
            if ($totalPayments >= $purchase->grand_total) {
                $purchase->payment_status = 'Paid';
            } else {
                $purchase->payment_status = 'Partial';
            }
            $purchase->paid_amount +=$this->paymentAmount;
        
            $purchase->save();
        });

        $this->dispatch('show-toastr', ['message' => 'Payment successfully added.']);
        $this->showCreatePaymentModal = false; // Close the modal

        $this->dispatch('paymentSuccessful');
    }

    public function mount()
    {
        // Load payment methods when the component is initialized
        $this->paymentMethods = PaymentMethod::all();
    }

    public function render()
    {
        return view('livewire.create-purchase-payment');
    }
}
