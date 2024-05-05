<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([CustomerSeeder::class,
         WarehouseSeeder::class,
         SettingsTableSeeder::class,
        // AppSeeder::class,
        // ProductSeeder::class,
         PaymentMethodSeeder::class,
         CurrencySeeder::class,
         PermissionsGroupSeeder::class,
         PermissionsTableSeeder::class,
         RolesTableSeeder::class,
         // UserSeeder::class
        ]);
    }

    
}
