<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware(['checkModule:brands','can:manage_brands']);
    }

    public function index(Request $request)
    {
  
        return view('brands.index' );
    }
}
