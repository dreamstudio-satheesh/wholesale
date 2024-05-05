<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
   
    public function __construct()
    {
        $this->middleware(['checkModule:warehouses', 'can:manage_warehouses']);
    }


    public function index()
    {
        return view('warehouses.index');
    }

    
}
