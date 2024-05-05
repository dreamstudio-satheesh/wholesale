<?php

namespace App\Livewire;

use App\Models\Stock; // Make sure to use your actual Stock model
use Livewire\Component;
use Livewire\WithPagination;

class StockHistory extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
    }
    public function render()
    {
        $userCanViewAllStocks = auth()->user()->hasPermissionTo('view_all_stocks');
        $stocks = Stock::with('product', 'warehouse') // Assuming relationships are set up in the Stock model
            ->when(!$userCanViewAllStocks, function ($query) {
                return $query->where('created_by', auth()->id()); // Filter to current user's sales
            })
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('stocks.view-stockhistory', compact('stocks'));
    }
}
