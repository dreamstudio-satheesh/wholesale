<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module)
    {
        $moduleSettings = [
            'categories' => 'categories_enabled',
            'brands' => 'brands_enabled',
            'warehouses' => 'warehouses_enabled',
            'units' => 'units_enabled',
            'stocks' => 'stocks_enabled',
            'pos' => 'pos_enabled',
            'purchases' => 'purchases_enabled',
            'accounting' => 'accounting_enabled',
        ];

        $settingKey = $moduleSettings[$module] ?? null;
    
        if (!$settingKey || Setting::where('key', $settingKey)->first()->value !== 'true') {
            // Redirect or abort with a message specific to the disabled module
            return redirect()->route('home')->with('error', ucfirst($module) . ' module is disabled.');
        }
    
        return $next($request);
    }
    
}
