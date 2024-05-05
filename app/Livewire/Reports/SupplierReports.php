<?php

namespace App\Livewire\Reports;

use Log;
use Carbon\Carbon;
use App\Models\Purchase;
use Livewire\Component;
use App\Models\Supplier;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class SupplierReports extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $search = '';
    public $sortField = 'id'; // default sort field
    public $sortDirection = 'desc'; // or 'desc'
    protected $paginationTheme = 'bootstrap';

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
    }

    public function render()
    {
        $query = Supplier::query()
            ->with([
                'purchases' => function ($query) {
                    // Apply date filter to the loaded purchases relationship
                    $query->when($this->startDate && $this->endDate, function ($query) {
                        $query->whereBetween('date', [Carbon::parse($this->startDate)->startOfDay(), Carbon::parse($this->endDate)->endOfDay()]);
                    });
                },
            ])
            ->whereHas('purchases', function ($query) {
                // Ensure aggregate calculations consider only purchases within this date range
                $query->when($this->startDate && $this->endDate, function ($query) {
                    $query->whereBetween('date', [Carbon::parse($this->startDate)->startOfDay(), Carbon::parse($this->endDate)->endOfDay()]);
                });
            })
            ->withCount('purchases as total_purchases') // This will count all related salespurchases
            ->selectRaw(
                'suppliers.*,
                (SELECT SUM(grand_total) FROM purchases WHERE purchases.supplier_id = suppliers.id AND purchases.date BETWEEN ? AND ?) as total_amount,
                (SELECT SUM(paid_amount) FROM purchases WHERE purchases.supplier_id = suppliers.id AND purchases.date BETWEEN ? AND ?) as total_paid,
                (SELECT SUM(grand_total - paid_amount) FROM purchases WHERE purchases.supplier_id = suppliers.id AND purchases.date BETWEEN ? AND ?) as total_purchase_due',
                [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay(), // Parameters for total_amount
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay(), // Parameters for total_paid
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay(), // Parameters for total_sale_due
                ],
            );

        // Apply search filters
        if (!empty($this->search)) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Ordering and pagination
        $suppliers = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.reports.supplier-reports', compact('suppliers'));
    }
}

   