<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

  

    protected $fillable = ['key', 'value'];


    public static function getModuleStatuses()
    {
        $modules = ['categories', 'brands', 'units', 'stocks', 'pos', 'purchases'];
        $statuses = [];

        foreach ($modules as $module) {
            $statuses[$module] = Setting::where('key', $module . '_enabled')->first()->value === 'true';
        }

        return $statuses;
    }


}
