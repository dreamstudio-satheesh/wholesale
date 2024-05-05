<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\ModuleStatusService;
use App\Models\Stock; // Make sure to use your actual Stock model

class ViewStock extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
    }
    public function render()
    {
        $moduleStatuses = ModuleStatusService::getModuleStatuses();
        $warehouseEnabled = $moduleStatuses['warehouses'] ?? false;
    
        $query = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id');
    
        // Conditionally join the warehouses table based on the module status
        if ($warehouseEnabled) {
            $query->leftJoin('warehouses', 'stocks.warehouse_id', '=', 'warehouses.id');
        }
    
        $query->select(
            'stocks.product_id',
            'products.name as product_name',
            'products.sku as product_sku',
            DB::raw('SUM(stocks.quantity) as current_stock')
        );
    
        // Include warehouse information in the select and groupBy clauses only if the warehouse module is enabled
        if ($warehouseEnabled) {
            $query->addSelect('stocks.warehouse_id', 'warehouses.name as warehouse_name');
            $query->groupBy('stocks.warehouse_id', 'warehouses.name');
        }
    
        // Always group by these fields
        $query->groupBy('stocks.product_id', 'products.name', 'products.sku');
    
        $stocks = $query->paginate(10);
        
        return view('stocks.view-stock', compact('stocks'));
    }
    
}
