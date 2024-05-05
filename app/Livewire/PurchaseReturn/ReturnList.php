<?php

namespace App\Livewire\PurchaseReturn;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseReturn; // Replace with your actual PurchaseReturn model

class ReturnList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'return_invoice_number'; // default sort field
    public $sortDirection = 'desc'; // or 'desc'
    protected $paginationTheme = 'bootstrap';


    protected $listeners = ['paymentSuccessful' => '$refresh', 'deletePurchase' => 'handleDeletePurchase'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function createPayment($saleId)
    {
        $this->dispatch('openModal',$saleId);
    }

    public function handleDeletePurchase($id)
    {
        $purchase = PurchaseReturn::find($id);
        if ($purchase) {
            $purchase->deleted_by = Auth::id();
            $purchase->save();
            $purchase->delete();
            $this->dispatch('show-toastr', ['message' => 'Purchase Return deleted.']);
        } else {
            $this->dispatch('show-toastr', ['message' => 'Purchase Return not found.']);
        }
    }


    public function showPayment($paymentId)
    {
        
        $this->dispatch('showModal',$paymentId);
    } 

    public function render()
    {
        $purchasesreturn = PurchaseReturn::with(['supplier', 'warehouse', 'user'])
            ->Where('grand_total', 'like', '%' . $this->search . '%')
            ->orWhere('return_invoice_number', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('purchases.purchases-return-list', compact('purchasesreturn'));
    }
}
