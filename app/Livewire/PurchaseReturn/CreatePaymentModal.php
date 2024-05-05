<?php

namespace App\Livewire\PurchaseReturn;

use App\Models\PurchaseReturn;
use Livewire\Component;
use App\Models\PurchaseReturnPayment;
use App\Models\PaymentMethod;
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
        $purchasereturn = PurchaseReturn::find($purchaseId);
        if ($purchasereturn) {
            $this->paymentAmount = $purchasereturn->pendingPaymentAmount();
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
            // Fetch the sale record once
            $purchasereturn = PurchaseReturn::with('payments')->find($this->purchaseId);
        
            if (!$purchasereturn) {
                // Optionally handle the case where the sale doesn't exist
                return;
            }

       
        
            // Create the new payment sale record
            $paymentSale = new PurchaseReturnPayment([
                'purchase_return_id' => $purchasereturn->id,
                'user_id' => Auth::user()->id,
                'amount' => $this->paymentAmount,
                'date' => $this->paymentDate,
                'payment_method_id' => $this->paymentChoice,
                'payment_notes' => $this->paymentNotes,
                'notes' => '', // Assuming you want to capture additional purchases-related notes
            ]);
            $paymentSale->save();

            $totalPayments = $purchasereturn->payments->sum('amount') + $this->paymentAmount;
        
            // Update the sale's payment status based on the new total payments
            if ($totalPayments >= $purchasereturn->grand_total) {
                $purchasereturn->payment_status = 'Paid';
            } else {
                $purchasereturn->payment_status = 'Partial';
            }
            $purchasereturn->paid_amount +=$this->paymentAmount;
        
            $purchasereturn->save();
        });

        $this->dispatch('show-toastr', ['message' => 'Payment successfully added.']);
       
        $this->dispatch('paymentSuccessful');
        // Close the modal
        $this->showCreatePaymentModal = false;
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
