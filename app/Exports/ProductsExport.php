<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select('id','name','sku','cost','price','category_id','brand_id')->get();
    }

    public function headings(): array
    {
        // Specify the column headings
        return [
            'ID',
            'Name',
            'SKU',
            'Cost',
            'Price',
            'Category ID',
            'Brand ID',
        ];
    }
}
