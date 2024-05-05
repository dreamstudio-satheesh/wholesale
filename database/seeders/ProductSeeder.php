<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       /*  Product::factory()
            ->count(20)
            ->create()
            ->each(function ($product) {
                // Assuming you have a collection named 'products'
                $this->addRandomImage($product, 'products');

                // Add 10 stock items for each product
                for ($i = 0; $i < 10; $i++) {
                    $product->stocks()->create([
                        'variant_id' => null, // Adjust as necessary
                        'warehouse_id' => 1, // or any specific logic to assign warehouses
                        'date' => now(), // Current date and time
                        'quantity' => 100, // Example quantity, adjust as necessary
                        'type' => 'Addition', // Assuming this is stock being added
                        'movement_reason' => 'Purchase', // Example reason, adjust as necessary
                        'related_order_id' => null, // Adjust as necessary
                        'created_by' => 1, // Example user ID, adjust as necessary
                    ]);
                }
            }); */


              
    }

    protected function addRandomImage($product, $collectionName)
    {
        $imagesDirectory = database_path('seeders/images/products'); // Adjust the path as necessary
        $images = File::files($imagesDirectory);

        if (count($images) > 0) {
            // Select a random image
            $image = $images[array_rand($images)];

            // Add the image to the product
            $product->addMedia($image->getPathname())->preservingOriginal()->toMediaCollection($collectionName);
        }
    }
}
