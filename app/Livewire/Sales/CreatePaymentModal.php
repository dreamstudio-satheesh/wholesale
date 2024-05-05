<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use App\Models\PaymentSale;
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
        $sale = Sale::find($saleId);
        if ($sale) {
            $this->paymentAmount = $sale->pendingPaymentAmount();
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
            $sale = Sale::with('payments')->find($this->saleId);
        
            if (!$sale) {
                // Optionally handle the case where the sale doesn't exist
                return;
            }

       
        
            // Create the new payment sale record
            $paymentSale = new PaymentSale([
                'sale_id' => $sale->id,
                'user_id' => Auth::user()->id,
                'amount' => $this->paymentAmount,
                'date' => $this->paymentDate,
                'payment_method_id' => $this->paymentChoice,
                'payment_notes' => $this->paymentNotes,
                'notes' => '', // Assuming you want to capture additional sales-related notes
            ]);
            $paymentSale->save();

            $totalPayments = $sale->payments->sum('amount') + $this->paymentAmount;
        
            // Update the sale's payment status based on the new total payments
            if ($totalPayments >= $sale->grand_total) {
                $sale->payment_status = 'Paid';
            } else {
                $sale->payment_status = 'Partial';
            }
            $sale->paid_amount +=$this->paymentAmount;
        
            $sale->save();
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
        return view('livewire.sales-payment-modal');
    }
}
