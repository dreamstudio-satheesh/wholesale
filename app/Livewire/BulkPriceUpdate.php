<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class BulkPriceUpdate extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name'; // default sort field
    public $sortDirection = 'asc'; // or 'desc'
    protected $paginationTheme = 'bootstrap';

    public $updatedPrices = []; // Array to hold updated prices

    public $updatedCosts = []; // To hold updated costs

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatePrice($productId)
    {
        $product = Product::find($productId);

        if (array_key_exists($productId, $this->updatedPrices)) {
            $product->price = $this->updatedPrices[$productId];
        }

        if (array_key_exists($productId, $this->updatedCosts)) {
            $product->cost = $this->updatedCosts[$productId];
        }

        $product->save();

        foreach ($product->variants as $variant) {
            if (isset($this->updatedPrices['variant'][$variant->id])) {
                $variant->price = $this->updatedPrices['variant'][$variant->id];
            }
            if (isset($this->updatedCosts['variant'][$variant->id])) {
                $variant->cost = $this->updatedCosts['variant'][$variant->id];
            }
            $variant->save();
        }
        $this->dispatch('show-toastr', ['message' =>  $product->name.' updated successfully']);
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

    public function render()
    {
        $products = Product::with(['variants'])
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(100);

        // Initialize updatedPrices with the current prices
        foreach ($products as $product) {
            $this->updatedPrices[$product->id] = $product->price;
            $this->updatedCosts[$product->id] = $product->cost; // Initialize cost

            foreach ($product->variants as $variant) {
                $this->updatedPrices['variant'][$variant->id] = $variant->price;
                $this->updatedCosts['variant'][$variant->id] = $variant->cost; // Initialize variant cost
            }
        }

        return view('products.bulk-price-update', compact('products'));
    }
}
