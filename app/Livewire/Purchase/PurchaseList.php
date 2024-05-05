<?php

namespace App\Livewire\Purchase;

use Livewire\Component;
use App\Models\Purchase;
use Livewire\WithPagination;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\Auth;

class PurchaseList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id'; // default sort field
    public $sortDirection = 'desc'; // or 'desc'
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['paymentSuccessful' => '$refresh' , 'deletePurchase' => 'handleDeletePurchase'];

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

    public function handleDeletePurchase($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase) {
            $purchase->deleted_by = Auth::id();
            $purchase->save();
            $purchase->delete();
            $this->dispatch('show-toastr', ['message' => 'Purchase deleted.']);
        } else {
            $this->dispatch('show-toastr', ['message' => 'Purchase not found.']);
        }
    }

    public function createPayment($saleId)
    {
        $this->dispatch('openModal', $saleId, 'create-purchase-payment');
    }

    public function showPayment($paymentId)
    {
        $this->dispatch('showModal', $paymentId, 'show-purchase-payment');
    }

    public function render()
    {
        $userCanViewAllPurchase = auth()->user()->hasPermissionTo('view_all_purchase');

        $purchases = Purchase::with(['supplier', 'warehouse', 'user'])
            ->when(!$userCanViewAllPurchase, function ($query) {
                return $query->where('user_id', auth()->id()); // Filter to current user's sales
            })
            ->Where('grand_total', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->addSelect(['has_return' => PurchaseReturn::selectRaw('count(*)')->whereColumn('purchase_returns.purchase_id', 'purchases.id')->limit(1)])
            ->paginate(10);

        return view('purchases.purchase-list', compact('purchases'));
    }
}
