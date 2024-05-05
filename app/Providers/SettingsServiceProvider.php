<?php

namespace App\Providers;

use Config;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $installed = Storage::disk('public')->exists('installed');

        if ($installed === true) {
            // To avoid a common issue when running migrations fresh
            if (Schema::hasTable('settings')) {
                // Fetch settings and convert them into an associative array with key => value
                $settings = Setting::pluck('value', 'key')->toArray();

                // Set each setting into the 'settings' config array
                foreach ($settings as $key => $value) {
                    config(['settings.' . $key => $value]);
                }

                // Dynamically set the application's timezone if it's set in settings
                if (isset($settings['time_zone'])) {
                    Config::set('app.timezone', $settings['time_zone']);
                    date_default_timezone_set($settings['time_zone']);
                }
            }
        }
    }
}
