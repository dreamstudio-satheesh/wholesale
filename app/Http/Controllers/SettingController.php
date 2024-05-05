<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:system_settings')->only('system');
        $this->middleware('can:module_settings')->only('module');
    }    
    public function module()
    {
        return view('settings.module');
    }

    public function system()
    {
        $settings = Setting::pluck('value', 'key');
        $jsonPath = public_path('timezones.json');
        $jsonContent = file_get_contents($jsonPath);
        $zonesArray = json_decode($jsonContent, true);

        $defaultWarehouseId = $settings['default_warehouse'] ?? null;
        $defaultCustomerId = $settings['default_customer'] ?? null;
        $defaultCurrencyCode = $settings['default_currency'] ?? null;

        // Fetch entities from the database
        $warehouses = Warehouse::all();
        $customers = Customer::all();
        $currencies = Currency::all();
        return view('settings.system', compact('settings', 'zonesArray', 'warehouses', 'customers', 'currencies', 'defaultWarehouseId', 'defaultCustomerId', 'defaultCurrencyCode'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'default_currency' => 'required|exists:currencies,code',
            'company_name' => 'required|string',
            'developed_by' => 'required|string',
            'time_zone' => 'required|string',
            'company_address' => 'required|string',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string',
            'company_vat' => 'nullable|string',
            'app_name' => 'required|string',
            'app_footer' => 'nullable|string',
            'default_customer' => 'required|exists:customers,id',
            'default_warehouse' => 'required|exists:warehouses,id',
        ]);

        if (is_null($validatedData['company_email'])) {
            $validatedData['company_email'] = ' ';
        }
        
        if (is_null($validatedData['company_phone'])) {
            $validatedData['company_phone'] = ' ';
        }

        if (is_null($validatedData['company_vat'])) {
            $validatedData['company_vat'] = ' ';
        }

        if (is_null($validatedData['app_footer'])) {
            $validatedData['app_footer'] = ' ';
        }

        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Fetch and update the currency symbol
        if (isset($validatedData['default_currency'])) {
            $currencyCode = $validatedData['default_currency'];
            $currencySymbol = Currency::where('code', $currencyCode)->value('symbol');
            if ($currencySymbol) {
                Setting::updateOrCreate(['key' => 'currency_symbol'], ['value' => $currencySymbol]);
            }
        }

        if ($request->hasFile('logo')) {
            $logoName = 'logo.' . $request->logo->extension();
            $request->logo->move(public_path('images'), $logoName);
            Setting::updateOrCreate(['key' => 'logo'], ['value' => $logoName]);
        }

        return redirect()
            ->back()
            ->with('success', 'Settings updated successfully.');
    }

    public function show(Setting $setting)
    {
        //
    }

    public function edit(Setting $setting)
    {
        //
    }

    public function update(Request $request)
    {
        $categoriesEnabled = $request->has('categories_enabled');
        Setting::where('key', 'categories_enabled')->update(['value' => $categoriesEnabled ? 'true' : 'false']);

        // Redirect back with success message
    }

    public function destroy(Setting $setting)
    {
        //
    }
}
