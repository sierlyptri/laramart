<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => Str::slug('Electronics'),
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'slug' => Str::slug('Clothing'),
        ]);

        $food = Category::create([
            'name' => 'Food & Beverage',
            'slug' => Str::slug('Food & Beverage'),
        ]);

        $accessories = Category::create([
            'name' => 'Accessories',
            'slug' => Str::slug('Accessories'),
        ]);

        // Create products
        Product::create([
            'name' => 'Wireless Headphones',
            'slug' => 'wireless-headphones-' . time(),
            'description' => 'Noise-cancelling over-ear headphones with 30-hour battery life. Perfect for music lovers and professionals.',
            'price' => 129.00,
            'stock' => 25,
            'category_id' => $electronics->id,
        ]);

        Product::create([
            'name' => 'Sample T-Shirt',
            'slug' => 'sample-t-shirt-' . (time() + 1),
            'description' => 'Comfortable cotton t-shirt with premium quality fabric. Available in multiple colors and sizes.',
            'price' => 19.99,
            'stock' => 100,
            'category_id' => $clothing->id,
        ]);

        Product::create([
            'name' => 'Water Bottle',
            'slug' => 'water-bottle-' . (time() + 2),
            'description' => 'Insulated stainless steel water bottle keeps drinks cold for 24 hours. Eco-friendly and durable.',
            'price' => 24.50,
            'stock' => 50,
            'category_id' => $accessories->id,
        ]);

        Product::create([
            'name' => 'USB-C Charging Cable',
            'slug' => 'usb-c-charging-cable-' . (time() + 3),
            'description' => 'Fast charging USB-C cable compatible with all USB-C devices. 2-meter length for convenience.',
            'price' => 12.99,
            'stock' => 150,
            'category_id' => $electronics->id,
        ]);

        Product::create([
            'name' => 'Blue Denim Jeans',
            'slug' => 'blue-denim-jeans-' . (time() + 4),
            'description' => 'Classic blue denim jeans with comfortable fit. Perfect for casual everyday wear.',
            'price' => 49.99,
            'stock' => 80,
            'category_id' => $clothing->id,
        ]);

        Product::create([
            'name' => 'Premium Coffee Beans',
            'slug' => 'premium-coffee-beans-' . (time() + 5),
            'description' => 'Freshly roasted premium arabica coffee beans from the finest origins. 1kg package.',
            'price' => 34.99,
            'stock' => 45,
            'category_id' => $food->id,
        ]);

        Product::create([
            'name' => 'Organic Tea Set',
            'slug' => 'organic-tea-set-' . (time() + 6),
            'description' => 'Complete organic tea set with loose leaf assortment and brewing infuser.',
            'price' => 28.50,
            'stock' => 30,
            'category_id' => $food->id,
        ]);

        Product::create([
            'name' => 'Smartwatch',
            'slug' => 'smartwatch-' . (time() + 7),
            'description' => 'Advanced smartwatch with health tracking, fitness modes, and 7-day battery life.',
            'price' => 199.99,
            'stock' => 20,
            'category_id' => $electronics->id,
        ]);

        Product::create([
            'name' => 'Winter Jacket',
            'slug' => 'winter-jacket-' . (time() + 8),
            'description' => 'Warm and stylish winter jacket with water-resistant coating. Perfect for cold weather.',
            'price' => 89.99,
            'stock' => 35,
            'category_id' => $clothing->id,
        ]);

        Product::create([
            'name' => 'Leather Wallet',
            'slug' => 'leather-wallet-' . (time() + 9),
            'description' => 'Premium leather wallet with RFID protection. Stylish and durable design.',
            'price' => 44.99,
            'stock' => 60,
            'category_id' => $accessories->id,
        ]);
    }
}
