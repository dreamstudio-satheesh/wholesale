<?php

namespace App\Livewire\SalesReturn;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Models\SalesReturn; // Replace with your actual SalesReturn model

class SalesReturnList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'return_invoice_number'; // default sort field
    public $sortDirection = 'desc'; // or 'desc'
    protected $paginationTheme = 'bootstrap';


    protected $listeners = ['paymentSuccessful' => '$refresh','deleteSalesReturn' => 'handleDeleteSalesReturn'];

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

    public function showPayment($paymentId)
    {
        
        $this->dispatch('showModal',$paymentId);
    } 

    public function handleDeleteSalesReturn($id)
    {
        $return = SalesReturn::find($id);

        if ($return) {

         // Delete all associated sale return items
         $return->items()->delete();
         // Now delete the sale itself
         $return->delete();

        $this->dispatch('show-toastr', ['message' => 'Sales Return successfully Removed.']);
        }
    }

    public function render()
    {
        $salesreturn = SalesReturn::with(['customer', 'warehouse', 'user'])
            ->Where('grand_total', 'like', '%' . $this->search . '%')
            ->orWhere('return_invoice_number', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('salesreturn.list', compact('salesreturn'));
    }
}
