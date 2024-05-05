<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        // Empty the table before seeding to avoid duplicates
        DB::table('payment_methods')->truncate();

        $paymentMethods = [
            ['title' => 'Cash', 'is_default' => 1],
            ['title' => 'Credit Card', 'is_default' => 0],
            ['title' => 'Check', 'is_default' => 0],
            ['title' => 'Bank Transfer', 'is_default' => 0],
            // ... add as many as you need
        ];

        foreach ($paymentMethods as $method) {
            DB::table('payment_methods')->insert([
                'title' => $method['title'],
                'is_default' => $method['is_default'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
