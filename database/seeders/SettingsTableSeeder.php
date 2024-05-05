<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->insert([
            ['key' => 'company_name', 'value' => 'Your Company Name'],
            ['key' => 'company_email', 'value' => 'contact@yourcompany.com'],
            ['key' => 'company_phone', 'value' => '12345678890'],
            ['key' => 'company_vat', 'value' => 'PL6541215450'],
            ['key' => 'company_address', 'value' => '123, Street Name, Area'],
            ['key' => 'company_pincode', 'value' => '123456'],
            ['key' => 'company_gst_no', 'value' => '22AAAAA0000A1Z5'],
            ['key' => 'company_state', 'value' => 'Your State'],
            ['key' => 'company_country', 'value' => 'India'],
            ['key' => 'app_name', 'value' => 'Elite POS'],
            ['key' => 'developed_by', 'value' => 'Dream Coderz'],
            ['key' => 'app_footer', 'value' => ''],
            ['key' => 'default_customer', 'value' => '1'],
            ['key' => 'default_warehouse', 'value' => '1'],
            ['key' => 'default_currency', 'value' => 'INR'],
            ['key' => 'currency_symbol', 'value' => '₹'],
            
            [ 'key' => 'categories_enabled', 'value' => 'true'],
            [ 'key' => 'warehouses_enabled', 'value' => 'true'],
            [ 'key' => 'brands_enabled', 'value' => 'true'],
            [ 'key' => 'stocks_enabled', 'value' => 'flase'],
            [ 'key' => 'units_enabled', 'value' => 'true'],
            [ 'key' => 'purchases_enabled', 'value' => 'true'],
            [ 'key' => 'pos_enabled', 'value' => 'true'],
            [ 'key' => 'accounting_enabled', 'value' => 'true'],
            //  more default settings as needed

            ['key' => 'invoice_prefix', 'value' => ''], 
            ['key' => 'invoice_suffix', 'value' => ''],
            ['key' => 'last_invoice_number', 'value' => '1000'], 
            ['key' => 'time_zone', 'value' => 'Asia/Kolkata'], 
        ]);
    }
}
