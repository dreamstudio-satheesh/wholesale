<?php

namespace App\Livewire\SalesReturn;

use App\Models\SalesReturn;
use Livewire\Component;
use App\Models\SalesReturnPayment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreatePaymentModal extends Component
{
    public $showCreatePaymentModal = false;
    public $saleId;
    public $paymentDate;
    public $paymentChoice;
    public $paymentAmount;
    public $paymentNotes;
    public $paymentMethods = [];

    protected $listeners = ['openModal' => 'prepareModal'];

    public function prepareModal($saleId)
    {
        $this->saleId = $saleId;
        $this->resetInputFields();
        $this->loadPendingPaymentAmount($saleId);
        $this->showCreatePaymentModal = true;
    }

    protected function loadPendingPaymentAmount($saleId)
    {
        $salesreturn = SalesReturn::find($saleId);
        if ($salesreturn) {
            $this->paymentAmount = $salesreturn->pendingPaymentAmount();
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
            $salesreturn = SalesReturn::with('payments')->find($this->saleId);
        
            if (!$salesreturn) {
                // Optionally handle the case where the sale doesn't exist
                return;
            }

       
        
            // Create the new payment sale record
            $paymentSale = new SalesReturnPayment([
                'sales_return_id' => $salesreturn->id,
                'user_id' => Auth::user()->id,
                'amount' => $this->paymentAmount,
                'date' => $this->paymentDate,
                'payment_method_id' => $this->paymentChoice,
                'payment_notes' => $this->paymentNotes,
                'notes' => '', // Assuming you want to capture additional sales-related notes
            ]);
            $paymentSale->save();

            $totalPayments = $salesreturn->payments->sum('amount') + $this->paymentAmount;
        
            // Update the sale's payment status based on the new total payments
            if ($totalPayments >= $salesreturn->grand_total) {
                $salesreturn->payment_status = 'Paid';
            } else {
                $salesreturn->payment_status = 'Partial';
            }
            $salesreturn->paid_amount +=$this->paymentAmount;
        
            $salesreturn->save();
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
        return view('livewire.sales-payment-modal');
    }
}
