<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ModuleStatusService
{
    public static function getModuleStatuses()
    {
        $installed = Storage::disk('public')->exists('installed');
        if ($installed === true) {
            if (!App::runningInConsole() || $installed === true) {
                // Define a cache key
                $cacheKey = 'module_statuses';
    
                // Retrieve from cache or resolve and cache if not present
                return Cache::remember($cacheKey, 60, function () { // Caches for 1 hour
                    $modules = ['categories', 'warehouses', 'brands', 'units', 'stocks', 'pos', 'purchases','accounting'];
                    $statuses = [];
    
                    foreach ($modules as $module) {
                        $setting = Setting::where('key', $module . '_enabled')->first();
                        $statuses[$module] = $setting ? $setting->value === 'true' : false;
                    }
    
                    return $statuses;
                });
            }
        }
        
       

        // Optionally handle console or other contexts
        return [];
    }
}
