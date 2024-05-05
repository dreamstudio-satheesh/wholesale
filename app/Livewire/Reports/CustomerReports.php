<?php

namespace App\Livewire\Reports;

use Log;
use Carbon\Carbon;
use App\Models\Sale;
use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CustomerReports extends Component
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
        $query = Customer::query()
            ->with([
                'sales' => function ($query) {
                    // Apply date filter to the loaded sales relationship
                    $query->when($this->startDate && $this->endDate, function ($query) {
                        $query->whereBetween('date', [Carbon::parse($this->startDate)->startOfDay(), Carbon::parse($this->endDate)->endOfDay()]);
                    });
                },
            ])
            ->whereHas('sales', function ($query) {
                // Ensure aggregate calculations consider only sales within this date range
                $query->when($this->startDate && $this->endDate, function ($query) {
                    $query->whereBetween('date', [Carbon::parse($this->startDate)->startOfDay(), Carbon::parse($this->endDate)->endOfDay()]);
                });
            })
            ->withCount('sales as total_sales') // This will count all related sales
            ->selectRaw(
                'customers.*,
                (SELECT SUM(grand_total) FROM sales WHERE sales.customer_id = customers.id AND sales.date BETWEEN ? AND ?) as total_amount,
                (SELECT SUM(paid_amount) FROM sales WHERE sales.customer_id = customers.id AND sales.date BETWEEN ? AND ?) as total_paid,
                (SELECT SUM(grand_total - paid_amount) FROM sales WHERE sales.customer_id = customers.id AND sales.date BETWEEN ? AND ?) as total_sale_due',
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
        $customers = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.reports.customer-reports', compact('customers'));
    }
}
