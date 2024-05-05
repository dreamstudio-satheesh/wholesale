<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ProductsExportPDF implements FromView
{
    public function view(): View
    {
        return view('exports.products', [
            'products' => Product::select('id','name','sku','cost','price','category_id','brand_id')->get()
        ]);
    }
}
