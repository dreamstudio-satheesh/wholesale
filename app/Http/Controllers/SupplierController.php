<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage_suppliers')->only('index');

    }
    public function index()
    {
        return view('suppliers.index');
    }

    public function addSupplier(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->save();

        // Return the new supplier's ID and name
        return response()->json(['id' => $supplier->id, 'name' => $supplier->name]);
    }
}
