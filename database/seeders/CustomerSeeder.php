<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the "Walk-in Customer" explicitly
        Customer::create([
            'name' => 'Walk-in Customer',
        ]);

        // Customer::factory()->count(10)->create();
        
    }
}
