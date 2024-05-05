<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting; // Ensure you have a Setting model

class SettingsManager extends Component
{
    public $settings;

    public function mount()
    {
        $allSettings = Setting::all()->pluck('value', 'key');

        $filteredKeys = ['categories_enabled', 'warehouses_enabled', 'brands_enabled', 'stocks_enabled', 'units_enabled', 'purchases_enabled', 'accounting_enabled', 'pos_enabled'];

        $this->settings = $allSettings
            ->filter(function ($value, $key) use ($filteredKeys) {
                return in_array($key, $filteredKeys);
            })
            ->map(function ($value) {
                return $value === 'true';
            })
            ->toArray();
    }

    public function updated($propertyName)
    {
        $key = explode('.', $propertyName)[1];
        $currentValue = $this->settings[$key];
        Setting::where('key', $key)->update(['value' => $this->settings[$key] ? 'true' : 'false']);
    }

    public function render()
    {
        return view('settings.settings-manager');
    }
}
