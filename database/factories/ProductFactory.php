<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        // Define an array of realistic product names
        $productNames = [
            'Fresh Apple',
            'Organic Rice',
            'Whole Wheat Bread',
            'Natural Oats',
            'Almond Milk',
            'Free-Range Eggs',
            'Quinoa Grain',
            'Greek Yogurt',
            'Green Tea',
            'Spaghetti Pasta',
            'Extra Virgin Olive Oil',
            'Dark Chocolate',
            'Roasted Coffee Beans',
            'Blueberries',
            'Kale Chips',
            'Cheddar Cheese',
            'Strawberry Jam',
            'Raw Honey',
            'Sea Salt',
            'Black Pepper',
            'Cinnamon Sticks',
            'Vanilla Extract',
            'Almonds',
            'Walnuts',
            'Dried Cranberries',
            'Basmati Rice',
            'Canned Tomatoes',
            'Brown Sugar',
            'Granola Bars',
            'Peanut Butter',
            'Soy Sauce',
            'Coconut Water',
            'Sparkling Water',
            'Herbal Tea',
            'Balsamic Vinegar',
            'Red Wine Vinegar',
            'Raisins',
            'Dried Apricots',
            'Pumpkin Seeds',
            'Sunflower Seeds',
            'Cashews',
            'Pecans',
            'Hazelnuts',
            'Pine Nuts',
            'Black Beans',
            'Chickpeas',
            'Lentils',
            'Kidney Beans',
            'Green Peas',
            'Cornflakes'
        ];

        $cost = $this->faker->randomFloat(2, 100, 1000);
        $price =  $cost+($cost * 0.05);
        
        return [
            'name' => $this->faker->randomElement($productNames),
            'sku' =>  $this->faker->unique()->numberBetween(1000, 9999),
            'description' => $this->faker->optional()->text,
            'product_type' => 'standard',
            'price' => $price, // Adjust the range for realistic prices
            'cost' => $cost, // Adjust the range for realistic product costs
            'tax_method' => $this->faker->randomElement(['exclusive', 'inclusive']),
            'tax' => $this->faker->randomFloat(2, 0, 25),
            'category_id' => $this->faker->optional()->numberBetween(1, 10),
            'brand_id' => $this->faker->optional()->numberBetween(1, 10),
            'unit_id' => $this->faker->optional()->numberBetween(1, 10),
            'unit_sale_id' => $this->faker->optional()->numberBetween(1, 10),
            'unit_purchase_id' => $this->faker->optional()->numberBetween(1, 10),
            'minimum_sale_quantity' => $this->faker->randomDigitNotNull,
            'stock_alert' => $this->faker->optional()->randomDigitNotNull,
        ];
    }
}

