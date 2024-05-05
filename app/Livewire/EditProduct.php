<?php

namespace App\Livewire;

use App\Models\Unit;
use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use App\Models\ProductVariant;
use Illuminate\Validation\Rule;

class EditProduct extends Component
{
    use WithFileUploads;

    public $productId;

    public $categories = [],
        $brands = [],
        $units = [],
        $saleUnits = [],
        $purchaseUnits = [],
        $variants = [];
    public $productName, $productCode, $category_id, $brand_id, $orderTax, $taxMethod, $image, $details;
    public $productType;
    public $productCost, $productPrice, $unit_id, $unit_sale_id, $unit_purchase_id, $minimumSaleQuantity, $stockAlert;

    public function saveProduct()
    {
        $this->brand_id = $this->brand_id ?: null;
        $this->unit_id = $this->unit_id ?: null;
        $this->unit_sale_id = $this->unit_sale_id ?: null;
        $this->unit_purchase_id = $this->unit_purchase_id ?: null;

        $validatedData = $this->validate([
            'productName' => 'required|string|max:255',
            'productCode' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->productId)],
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'orderTax' => 'required|numeric|min:0|max:100',
            'taxMethod' => 'required|in:exclusive,inclusive',
            'image' => 'nullable|image',
            'details' => 'nullable|string',
            'productType' => 'required|in:standard,variable,services',
            'productCost' => 'nullable|numeric|min:0',
            'productPrice' => 'nullable|numeric|min:0',
            'unit_id' => 'nullable',
            'unit_sale_id' => 'nullable',
            'unit_purchase_id' => 'nullable',
            'minimumSaleQuantity' => 'required|numeric|min:1',
            'stockAlert' => 'nullable|numeric|min:0',
        ]);

        if ($this->productType == 'variable') {
            $rules = [
                'variants.*.name' => 'required|string|max:255',
                'variants.*.cost' => 'required|numeric|min:0',
                'variants.*.price' => 'required|numeric|min:0',
            ];
            foreach ($this->variants as $index => $variant) {
                $variantId = $variant['id'] ?? null;
                $rules['variants.' . $index . '.code'] = ['required', 'string', 'max:255', Rule::unique('product_variants', 'sku')->ignore($variantId)];
            }

            $this->validate($rules);
        }

        $product = Product::find($this->productId);
        $previousProductType = $product->product_type; // store the previous product type

        $product->name = $validatedData['productName'];
        $product->sku = $validatedData['productCode'];
        $product->description = $validatedData['details'] ?? null;
        $product->product_type = $validatedData['productType'];
        $product->price = $validatedData['productPrice'];
        $product->cost = $validatedData['productCost'];
        $product->tax_method = $validatedData['taxMethod'];
        $product->tax = $validatedData['orderTax'];
        $product->category_id = $validatedData['category_id'] ?? null;
        $product->brand_id = $validatedData['brand_id'] ?? null;
        $product->unit_id = $validatedData['unit_id'] ?? null;
        $product->unit_sale_id = $validatedData['unit_sale_id'] ?? null;
        $product->unit_purchase_id = $validatedData['unit_purchase_id'] ?? null;
        $product->minimum_sale_quantity = $validatedData['minimumSaleQuantity'];
        $product->stock_alert = $validatedData['stockAlert'] ?? null;
        $product->save();

        // Check if product type was 'variable' and has now changed to 'standard' or 'service'
        if ($previousProductType == 'variable' && $this->productType != 'variable') {
            // Delete the associated variants
            ProductVariant::where('product_id', $product->id)->delete();
        }

        // If product type is variable, handle variants
        if ($this->productType == 'variable' && !empty($this->variants)) {
            foreach ($this->variants as $variant) {
                //  'sku' is the unique identifier for each variant, along with 'product_id'
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id, // The product the variant belongs to
                        'sku' => $variant['code'], // The unique identifier for the variant
                    ],
                    [
                        'name' => $variant['name'], // Other fields to update or create
                        'cost' => $variant['cost'],
                        'price' => $variant['price'],
                    ],
                );
            }
        }

        // Handle image upload if necessary
        if (!empty($validatedData['image'])) {

            // Fetch the first item from the 'products' media collection
            $oldMedia = $product->getFirstMedia('products');

            // Check if the product already has an image in the 'products' collection
            if ($oldMedia) {
                // Delete the old image
                $oldMedia->delete();
            }

            // Use addMediaFromDisk for Livewire's temporary uploaded file
            $product->addMedia($this->image->getRealPath())->usingFileName($this->image->getClientOriginalName())->toMediaCollection('products');
        }

        session()->flash('message', 'Product successfully updated.');

        return redirect()->to('/products');
    }

    public function addVariant()
    {
        $this->variants[] = ['name' => '', 'code' => '', 'cost' => '', 'price' => ''];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function updatedunit_id($value)
    {
        $baseUnit = Unit::find($value);

        if ($baseUnit) {
            $subUnits = Unit::where('base_unit', $value)->get();
            $this->saleUnits = collect([$baseUnit])->merge($subUnits);
            $this->purchaseUnits = collect([$baseUnit])->merge($subUnits);
        } else {
            $this->saleUnits = $this->units;
            $this->purchaseUnits = $this->units;
        }
    }

    public function mount($productId = null)
    {
        $this->categories = Category::all();
        $this->brands = Brand::all();
        $this->units = Unit::all();
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $this->productId = $productId;
                $this->productName = $product->name;
                $this->productCode = $product->sku;
                $this->category_id = $product->category_id;
                $this->brand_id = $product->brand_id;
                $this->taxMethod = $product->tax_method;
                $this->orderTax = $product->tax;
                $this->productType = $product->product_type;
                $this->productCost = $product->cost;
                $this->productPrice = $product->price;
                $this->unit_id = $product->unit_id;
                $this->unit_sale_id = $product->unit_sale_id;
                $this->unit_purchase_id = $product->unit_purchase_id;
                $this->minimumSaleQuantity = $product->minimum_sale_quantity;
                $this->stockAlert = $product->stock_alert;
                //$this->image = $product->image;
                // ... Set other properties from the product model
            }
        }

        if ($this->productType == 'variable') {
            $this->variants = $product->variants
                ->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'code' => $variant->sku,
                        'cost' => $variant->cost,
                        'price' => $variant->price,
                    ];
                })
                ->toArray();
        } else {
            $this->variants = [['name' => '', 'code' => '', 'cost' => '', 'price' => '']];
        }
    }

    public function render()
    {
        return view('products.edit-product', []);
    }
}
