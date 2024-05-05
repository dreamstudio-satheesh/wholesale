<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['checkModule:units', 'can:manage_units']);
    }

    public function index(Request $request)
    {
  
        return view('units.index' );
    }

}

