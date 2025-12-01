<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Ganteng',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password'), // passwordnya: password
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User Ganteng',
            'email' => 'user@toko.com',
            'password' => Hash::make('password'), // passwordnya: password
            'role' => 'customer',
        ]);

        $cat1 = Category::create(['name' => 'Gadget', 'slug' => 'gadget']);
        $cat2 = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
        $cat3 = Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);

        Product::create([
            'category_id' => $cat1->id,
            'name' => 'iPhone 15 Pro Max',
            'slug' => 'iphone-15-pro-max',
            'description' => 'HP sultan spek dewa, kamera jernih banget.',
            'price' => 25000000,
            'stock' => 10,
            'image' => 'iphone.jpg',
        ]);

        Product::create([
            'category_id' => $cat1->id,
            'name' => 'Samsung S24 Ultra',
            'slug' => 'samsung-s24-ultra',
            'description' => 'HP Android terbaik tahun ini dengan AI canggih.',
            'price' => 22000000,
            'stock' => 5,
            'image' => 'samsung.jpg',
        ]);

        Product::create([
            'category_id' => $cat2->id,
            'name' => 'Hoodie H&M Polos',
            'slug' => 'hoodie-hm-polos',
            'description' => 'Bahan katun nyaman dipake seharian.',
            'price' => 350000,
            'stock' => 50,
            'image' => 'hoodie.jpg',
        ]);

        Product::create([
            'category_id' => $cat3->id,
            'name' => 'Kulkas 2 Pintu',
            'slug' => 'kulkas-2-pintu',
            'description' => 'Dingin banget sampe ke tulang.',
            'price' => 3500000,
            'stock' => 3,
            'image' => 'kulkas.jpg',
        ]);
    }
}
