<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Services\ModuleStatusService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $moduleStatuses = ModuleStatusService::getModuleStatuses();
        View::share('moduleStatuses', $moduleStatuses);
       
    }
}
