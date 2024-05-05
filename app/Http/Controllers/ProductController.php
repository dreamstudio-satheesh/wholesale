<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use App\Exports\ProductsExport;
use App\Exports\ProductsExportPDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Traits\HandlesSalesOperations;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_products')->only('index');
        $this->middleware('can:create_products')->only(['create', 'store']);
        $this->middleware('can:edit_products')->only(['edit', 'update']);
        $this->middleware('can:delete_products')->only('destroy');
        // Add more middleware as needed for other permissions
    }
    
    public function index()
    {
        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function edit($id)
    {
        return view('products.edit',compact('id'));
    }


    public function updateprice()
    {
        return view('products.updateprice');
    }

    public function export($type) 
    {
        if ($type == 'excel') {
            return Excel::download(new ProductsExport, 'products.xlsx');
        }
        elseif ($type == 'csv') {
            return Excel::download(new ProductsExport, 'products.csv', \Maatwebsite\Excel\Excel::CSV);

        }
        elseif ($type == 'pdf') {
            return Excel::download(new ProductsExportPDF, 'products.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }
       
    }
}
