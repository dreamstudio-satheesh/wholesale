<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public $indianNames = ['Arjun Singh', 'Priya Sharma', 'Rahul Gupta', 'Anjali Patel', 'Suresh Kumar', 'Deepika Iyer', 'Vijay Raj', 'Kavita Krishnan', 'Mohit Verma', 'Ayesha Khan'];

    public function definition()
    {
        global $indianNames;
        static $index = 0;
        
        return [
            'name' => $indianNames[$index++ % count($indianNames)],
            'phone' => $this->faker->optional()->phoneNumber,
            'email' => $this->faker->optional()->safeEmail,
            'address' => $this->faker->optional()->address,
            'created_by' => $this->faker->optional()->randomDigitNotNull,
            'deleted_by' => $this->faker->optional()->randomDigitNotNull,
        ];
    }
}
