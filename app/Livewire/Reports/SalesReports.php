<?php

namespace App\Livewire\Reports;

use Log;
use Carbon\Carbon;
use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class SalesReports extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $search = '';
    public $sortField = 'invoice_number'; // default sort field
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
        $query = Sale::with(['customer', 'warehouse', 'user']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [
                Carbon::parse($this->startDate)->startOfDay(), 
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        if (!empty($this->search)) {
            $query->where(function ($query) {
                $query->where('grand_total', 'like', '%' . $this->search . '%')
                      ->orWhere('invoice_number', 'like', '%' . $this->search . '%');
            });
        }


    
        $sales = $query->orderBy($this->sortField, $this->sortDirection)
                   ->paginate(10);

        return view('livewire.reports.sales-reports', compact('sales'));
    }
}
