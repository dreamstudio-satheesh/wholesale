<?php

namespace App\Livewire;

use App\Models\Stock;
use App\Models\Product;
use Livewire\Component;
use App\Models\Warehouse;
use App\Models\ProductVariant;


class UpdateStock extends Component
{
    public $date;
    public $warehouse_id = 1;
    public $search;
    public $selectedProductId;
    public $selectedvariantId = null;
    public $currentStock;
    public $selectedProductName;
    public $selectedProductSKU;
    public $quantity;
    public $type = 'Addition';
    public $movement_reason;
    public $warehouses;
    public $searchResults = [];
    public $additionReasons = ['Stock Adjustment_Addition', 'Purchase', 'Sales Return', 'Transfer In'];

    public $subtractionReasons = ['Stock Adjustment_Subtraction', 'Sale', 'Purchase Return', 'Transfer Out', 'Write-Off'];

    public function mount()
    {
        $this->date = now()->format('Y-m-d H:i:s');
        $this->warehouses = Warehouse::all();
        $this->type = 'Addition'; // Default type
        $this->movement_reason = 'Stock Adjustment_Addition';
    }

    public function updatedType()
    {
        $this->movement_reason = $this->type === 'Addition' ? 'Stock Adjustment_Addition' : 'Stock Adjustment_Subtraction';
    }

    public function updatedSearch()
    {
        $this->searchResults = collect(); // Initialize as an empty collection

        if (strlen($this->search) > 2) {
            $products = Product::with(['variants'])
                ->where('sku', 'like', '%' . $this->search . '%')
                ->orWhere('name', 'like', '%' . $this->search . '%')
                ->get();

            foreach ($products as $product) {
                $this->appendProductWithVariant($product);
            }
        }
    }

    protected function appendProductWithVariant($product)
    {
        if ($product->variants->isNotEmpty()) {
            foreach ($product->variants as $variant) {
                $variantProduct = clone $product;
                $variantProduct->name = "{$product->name} - {$variant->name}";
                $variantProduct->sku = $variant->sku;
                $variantProduct->variant_id = $variant->id;

                // Append this variant to the search results
                $this->searchResults->push($variantProduct);
            }
        } else {
            // If no variants, just append the product itself
            $product->variant_id = null;
            $this->searchResults->push($product);
        }
    }

    public function selectProduct($productId, $variantId = null)
    {
        $this->selectedProductId = $productId;

        if (!is_null($variantId) && $variantId !== 'null') {
            // Find the variant based on variantId and get the parent product
            $variant = ProductVariant::find($variantId);
            $product = $variant->product; // Assuming you have a relationship set from variant to product

            // Set the selected product name and SKU to variant's details
            $this->selectedProductName = "{$product->name} - {$variant->name}";
            $this->selectedProductSKU = $variant->sku;
            $this->selectedvariantId = $variant->id;
        } else {
            // Handle base product selection
            $product = Product::find($productId);
            if ($product) {
                $this->selectedProductName = $product->name;
                $this->selectedProductSKU = $product->sku;
            }
        }

        $this->currentStock = $this->calculateCurrentStock($productId, $variantId, $this->warehouse_id);

        // Clear the search and results after selection
        $this->search = '';
        $this->searchResults = [];
    }

    private function calculateCurrentStock($productId, $variantId, $warehouseId)
    {
        $query = Stock::query();
        $query->where('product_id', $productId);

        if (is_null($variantId)) {
            $query->whereNull('variant_id');
        } else {
            $query->where('variant_id', $variantId);
        }

        if (!is_null($warehouseId)) {
            $query->where('warehouse_id', $warehouseId);
        }
        return $currentStock = $query->sum('quantity');
    }

    public function updateStock()
    {
        // Validate request...
        $this->validate([
            'date' => 'required|date',
            'warehouse_id' => 'required|numeric',
            'selectedProductId' => 'required',
            'quantity' => 'required|numeric|min:1',
            'type' => 'required|in:Addition,Subtraction',
            'movement_reason' => 'required|in:Sale,Purchase,Sales Return,Purchase Return,Stock Adjustment_Addition,Stock Adjustment_Subtraction,Transfer Out,Transfer In,Write-Off,Reconciliation',
            // other validation rules...
        ]);

        // Check if there's enough stock for subtraction
        if ($this->type === 'Subtraction') {
            $currentStock = Stock::where('product_id', $this->selectedProductId)
                ->where('warehouse_id', $this->warehouse_id)
                ->sum('quantity'); // Since subtraction quantities are stored as negative, summing them will give the current stock

            if ($this->quantity > $currentStock) {
                // Check if the subtraction quantity is more than the current stock
                $this->dispatch('showErrorToast', ['message' => 'Not enough stock available for subtraction.']);

                return; // Stop the execution if not enough stock
            }
        }

        // Stock updating logic...
        $stock = new Stock();
        $stock->product_id = $this->selectedProductId;
        $stock->variant_id = $this->selectedvariantId;
        $stock->quantity = $this->quantity * ($this->type === 'Subtraction' ? -1 : 1);
        $stock->type = $this->type;
        $stock->date = $this->date;
        $stock->warehouse_id = $this->warehouse_id;
        $stock->movement_reason = $this->movement_reason;
        $stock->created_by = auth()->user()->id;
        $stock->save();

        // Reset the form...
        $this->reset(['selectedProductId', 'selectedProductName', 'selectedProductSKU', 'quantity', 'type']);

        // Provide feedback to the user...
        $this->dispatch('show-toastr', ['message' => 'Stock updated successfully.']);
    }

    public function render()
    {
        return view('stocks.update-stock');
    }
}
