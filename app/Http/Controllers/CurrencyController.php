<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage_currencies');
    }

    public function index(): View
    {
        $currencies = Currency::orderBy('id', 'asc')->get();
        return view('currencies.index', compact('currencies'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'code' => 'required|max:10',
            'symbol' => 'required|max:10',
        ]);

        Currency::create($validatedData);

        return redirect()
            ->route('currency.index')
            ->with('success', 'Currency created successfully.');
    }

    public function update(Request $request, Currency $currency)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'code' => 'required|max:10',
            'symbol' => 'required|max:10',
        ]);

        $currency->update($validatedData);

        return redirect()
            ->route('currency.index')
            ->with('success', 'Currency updated successfully.');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()
            ->route('currency.index')
            ->with('success', 'Currency deleted successfully.');
    }
}
