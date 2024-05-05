<?php

namespace App\Livewire\Reports;

use Log;
use Carbon\Carbon;
use App\Models\Product;
use Livewire\Component;
use App\Models\Warehouse;
use Livewire\WithPagination;

class ProductReports extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $search = '';
    public $warehouseId = '';
    public $sortField = 'sku'; // default sort field
    public $sortDirection = 'desc'; // or 'desc'
    protected $paginationTheme = 'bootstrap';

    public $warehouse;

    protected $listeners = ['dateRangeSelected' => 'handleDateRangeSelection'];

    public function handleDateRangeSelection($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

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

    public function mount()
    {
        if (empty($this->startDate)) {
            $this->startDate = Carbon::today()->subDays(29)->toDateString(); // 30 days ago from today
            $this->endDate = Carbon::today()->toDateString(); // Today
        }
        if (!empty($this->warehouseId)) {
            $this->warehouse = Warehouse::where('id', $this->warehouseId)->first()->name;
        } else {
            $this->warehouse = 'All';
        }
    }

    /*    public function render()
    {
        $startDate = '';
        $endDate = '';
        $search = $this->search;
        $warehouseId = $this->warehouseId;

        // Perform the initial query with conditions
        $productsQuery = Product::query()
            ->when(!empty($startDate) && !empty($endDate), function ($query) use ($startDate, $endDate) {
                // Conditions for sales and purchases based on dates
                $query
                    ->whereHas('salesItems.sale', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
                    })
                    ->orWhereHas('purchasesItems.purchase', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
                    });
            })
            ->when(!empty($warehouseId), function ($query) use ($warehouseId) {
                // Conditions for sales and purchases based on warehouse ID
                $query
                    ->whereHas('salesItems.sale', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    })
                    ->orWhereHas('purchasesItems.purchase', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    });
            })
            ->when(!empty($search), function ($query) use ($search) {
                // Search condition for product name or SKU
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')->orWhere('sku', 'like', '%' . $search . '%');
                });
            })
            ->with(['category', 'salesItems.sale', 'purchasesItems.purchase']);

        // Paginate the query results
        $paginatedProducts = $productsQuery->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        // Transform the paginated items with necessary details
        $transformedProducts = $paginatedProducts->getCollection()->map(function ($product) {
            // Perform aggregation and include all necessary details
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'product_type' => $product->product_type,
                'category' => optional($product->category)->name,
                'qty_sold' => $product->salesItems->sum('quantity'),
                'amount_sold' => $product->salesItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                }),
                'qty_purchased' => $product->purchasesItems->sum('quantity'),
                'amount_purchased' => $product->purchasesItems->sum(function ($item) {
                    return $item->quantity * $item->cost;
                }),
            ];
        });

        // Set the transformed collection back to the paginator
        $paginatedProducts->setCollection($transformedProducts);

        $products = $paginatedProducts;


        return view('livewire.reports.products-reports', compact('products'));
    } */

    public function render()
    {
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $search = $this->search;
        $warehouseId = $this->warehouseId;

        $productsQuery = Product::query()
            ->when(!empty($search), function ($query) use ($search) {
                // Apply search criteria on product name or SKU
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->with(['category', 'salesItems.sale', 'purchasesItems.purchase']);

        // If warehouse ID is provided, ensure we're loading products related to that warehouse only
        if (!empty($warehouseId)) {
            $productsQuery
                ->whereHas('salesItems.sale', function ($q) use ($warehouseId) {
                    $q->where('warehouse_id', $warehouseId);
                })
                ->orWhereHas('purchasesItems.purchase', function ($q) use ($warehouseId) {
                    $q->where('warehouse_id', $warehouseId);
                });
        }

        $products = $productsQuery->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        $startDateParsed = !empty($startDate) ? Carbon::parse($startDate)->startOfDay() : null;
        $endDateParsed = !empty($endDate) ? Carbon::parse($endDate)->endOfDay() : null;

        $transformedProducts = $products->getCollection()->map(function ($product) use ($startDateParsed, $endDateParsed) {
            // Initialize quantities and amounts to 0
            $qtySold = 0;
            $amountSold = 0;
            $qtyPurchased = 0;
            $amountPurchased = 0;

            // Aggregate sales data
            foreach ($product->salesItems as $saleItem) {
                if (!$startDateParsed || !$endDateParsed || ($saleItem->sale && $saleItem->sale->date >= $startDateParsed && $saleItem->sale->date <= $endDateParsed)) {
                    $qtySold += $saleItem->quantity;
                    $amountSold += $saleItem->quantity * $saleItem->price;
                }
            }

            // Aggregate purchases data
            foreach ($product->purchasesItems as $purchaseItem) {
                if (!$startDateParsed || !$endDateParsed || ($purchaseItem->purchase && $purchaseItem->purchase->date >= $startDateParsed && $purchaseItem->purchase->date <= $endDateParsed)) {
                    $qtyPurchased += $purchaseItem->quantity;
                    $amountPurchased += $purchaseItem->quantity * $purchaseItem->price;
                }
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'product_type' => $product->product_type,
                'category' => optional($product->category)->name,
                'qty_sold' => $qtySold,
                'amount_sold' => $amountSold,
                'qty_purchased' => $qtyPurchased,
                'amount_purchased' => $amountPurchased,
            ];
        });

        // Update the collection in the paginator with transformed data
        $products->setCollection($transformedProducts);

        // Return the view with paginated products
        return view('livewire.reports.products-reports', compact('products'));
    }
}
