<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage_customers')->only('index');

    }

    public function index()
    {
        return view('customers.index');
    }

    public function addCustomer(Request $request)
    {
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->save();

        // Return the new customer's ID and name
        return response()->json(['id' => $customer->id, 'name' => $customer->name]);
    }
}
