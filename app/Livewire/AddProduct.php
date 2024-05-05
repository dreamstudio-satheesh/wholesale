<?php

namespace App\Livewire;

use App\Models\Unit;
use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use App\Models\ProductVariant;
use Illuminate\Validation\ValidationException;

class AddProduct extends Component
{
    use WithFileUploads;

    public $categories = [],
        $brands = [],
        $units = [],
        $saleUnits = [],
        $purchaseUnits = [],
        $variants = [];
    public $newCategoryName, $newBrandName;
    public $productName,
        $productCode,
        $category_id,
        $brand_id,
        $orderTax = '0',
        $taxMethod = 'exclusive',
        $image,
        $details;
    public $productType;
    public $productCost,
        $productPrice,
        $unit_id,
        $unit_sale_id,
        $unit_purchase_id,
        $minimumSaleQuantity = 1,
        $stockAlert = 0;

    public function createCategory()
    {
        $this->validate(['newCategoryName' => 'required|string|max:255']);
        $category = Category::create(['name' => $this->newCategoryName]);
        $this->newCategoryName = '';

        $this->categories = Category::all();
        $this->category_id = $category->id;
        $this->dispatch('categoryModalClose');
        $this->dispatch('show-toastr', ['message' => 'Category successfully added.']);
    }

    public function createBrand()
    {
        $this->validate(['newBrandName' => 'required|string|max:255']);
        $brand = Brand::create(['name' => $this->newBrandName]);
        $this->newBrandName = '';

        $this->brands = Brand::all();
        $this->brand_id = $brand->id;
        $this->dispatch('brandModalClose');
        $this->dispatch('show-toastr', ['message' => 'Brand successfully added.']);
    }

    public function saveProduct()
    {
        $rules = [
            'productName' => 'required|string|max:255',
            'productCode' => 'required|string|max:255|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'orderTax' => 'required|numeric|min:0|max:100',
            'taxMethod' => 'required|in:exclusive,inclusive',
            'image' => 'nullable|image',
            'details' => 'nullable|string',
            'productType' => 'required|in:standard,variable,services',
            'productCost' => 'nullable|numeric|min:0',
            'unit_id' => 'nullable',
            'unit_sale_id' => 'nullable',
            'unit_purchase_id' => 'nullable',
            'minimumSaleQuantity' => 'required|numeric|min:1',
            'stockAlert' => 'nullable|numeric|min:0',
        ];

        if ($this->productType == 'variable') {
            $rules['productPrice'] = 'nullable|numeric|min:0';
            $rules['variants.*.name'] = 'required|string|max:255';
            $rules['variants.*.code'] = 'required|string|max:255|unique:product_variants,sku';
            $rules['variants.*.cost'] = 'required|numeric|min:0';
            $rules['variants.*.price'] = 'required|numeric|min:0';
        } else {
            $rules['productPrice'] = 'required|numeric|min:0';
        }

        if ($this->brand_id === '') {
            $this->brand_id = null;
        }

        if ($this->category_id === '') {
            $this->category_id = null;
        }

        $validatedData = [];

        try {
            $validatedData = $this->validate($rules);
        } catch (ValidationException $exception) {
            // Get all the messages as an array
            $messages = $exception->validator->messages()->all();
            // Dispatch the event with the array of messages
            $this->dispatch('showErrorToast', $messages);
            return;
            // Return early if validation fails
        }
        
        $product = new Product();
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

        // If product type is variable, handle variants
        if ($this->productType == 'variable' && !empty($this->variants)) {
            foreach ($this->variants as $variant) {
                $productVariant = new ProductVariant();
                $productVariant->product_id = $product->id;
                $productVariant->name = $variant['name'];
                $productVariant->sku = $variant['code'];
                $productVariant->cost = $variant['cost'];
                $productVariant->price = $variant['price'];
                // Save each variant
                $productVariant->save();
            }
        }

      
        // Handle image upload if necessary
        if (!empty($validatedData['image'])) {
           
            // Use addMediaFromDisk for Livewire's temporary uploaded file
            $product->addMedia($this->image->getRealPath())->usingFileName($this->image->getClientOriginalName())->toMediaCollection('products');
        } 

        $this->reset();
        $this->dispatch('show-toastr', ['message' => 'Product successfully added.']);
        // $this->redirectRoute('products.create');

        $this->categories = Category::all();
        $this->brands = Brand::all();
        $this->units = Unit::all();
        $this->variants = [['name' => '', 'code' => '', 'cost' => '', 'price' => '']];
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

    public function updatedUnitId($value)
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

    public function mount()
    {
        $this->categories = Category::all();
        $this->brands = Brand::all();
        $this->units = Unit::all();
        $this->variants = [['name' => '', 'code' => '', 'cost' => '', 'price' => '']];
    }

    public function render()
    {
        return view('products.add-product', []);
    }
}
