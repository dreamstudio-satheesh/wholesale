<?php

namespace App\Livewire\Reports;

use App\Models\Product;
use Livewire\Component;
use App\Models\Warehouse;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class StockReports extends Component
{
    use WithPagination;

    public $search = '';
    public $warehouseId = '';
    public $warehouses;
    public $sortField = 'id'; // default sort field
    public $sortDirection = 'desc'; // or 'desc'
    protected $paginationTheme = 'bootstrap';

    public function SearchQuery()
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

    public function getWarehouseNameProperty()
    {
        if (!empty($this->warehouseId)) {
            $warehouse = Warehouse::find($this->warehouseId);
            return $warehouse ? $warehouse->name : 'Not Found';
        }

        return 'All';
    }

    public function mount()
    {
        $this->warehouses = Warehouse::all();
    }

    public function render()
    {
        $query = Product::query()
            ->select('products.*')
            ->with([
                'stocks' => function ($query) {
                    // Since negative quantities are used for subtraction, simply sum the quantity for current_stock.
                    $query->select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as current_stock'), \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN quantity > 0 THEN quantity ELSE 0 END) * (SELECT price FROM products WHERE id = product_id) as total_amount_stock'));
                    if ($this->warehouseId) {
                        $query->where('warehouse_id', $this->warehouseId);
                    }
                    $query->groupBy('product_id');
                },
            ]);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Pagination
        $products = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.reports.stock-reports', compact('products'));
    }
}
