<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Sample T-Shirt',
            'slug' => 'sample-t-shirt-' . time(),
            'description' => 'Comfortable cotton t-shirt',
            'price' => 19.99,
            'stock' => 100,
        ]);

        Product::create([
            'name' => 'Water Bottle',
            'slug' => 'water-bottle-' . time(),
            'description' => 'Insulated stainless steel bottle',
            'price' => 24.50,
            'stock' => 50,
        ]);

        Product::create([
            'name' => 'Wireless Headphones',
            'slug' => 'wireless-headphones-' . time(),
            'description' => 'Noise-cancelling over-ear headphones',
            'price' => 129.00,
            'stock' => 25,
        ]);
    }
}
