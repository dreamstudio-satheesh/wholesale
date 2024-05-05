<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\SalesReturn;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale; // Replace with your actual Sale model

class SalesList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'invoice_number'; // default sort field
    public $sortDirection = 'desc'; // or 'desc'
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['paymentSuccessful' => '$refresh', 'deleteSale' => 'handleDeleteSale'];

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
    public function handleDeleteSale($id)
    {
        $sale = Sale::find($id);
        if ($sale) {
            $sale->deleted_by = Auth::id();
            $sale->save();
            $sale->delete();
            $this->dispatch('show-toastr', ['message' => 'Sale deleted.']);
        } else {
            $this->dispatch('show-toastr', ['message' => 'Sale not found.']);
        }
    }

    public function createPayment($saleId)
    {
        $this->dispatch('openModal', $saleId);
    }

    public function showPayment($paymentId)
    {
        $this->dispatch('showModal', $paymentId);
    }

    public function render()
    {
        $userCanViewAllSales = auth()->user()->hasPermissionTo('view_all_sales');

        $sales = Sale::with(['customer', 'warehouse', 'user'])
            ->when(!$userCanViewAllSales, function ($query) {
                return $query->where('user_id', auth()->id()); // Filter to current user's sales
            })
            ->where(function ($query) {
                $query->where('grand_total', 'like', '%' . $this->search . '%')->orWhere('invoice_number', 'like', '%' . $this->search . '%');
            })
            ->addSelect(['has_return' => SalesReturn::selectRaw('count(*)')->whereColumn('sales_returns.sale_id', 'sales.id')->limit(1)])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('sales.sales-list', compact('sales'));
    }
}
