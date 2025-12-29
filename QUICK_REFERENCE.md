# QUICK REFERENCE: Code Snippets untuk Kategori & Filter

## Perubahan yang Sudah Dilakukan

Semua perubahan di bawah ini **SUDAH DILAKUKAN** pada proyek Anda.
File dokumentasi ini hanya untuk referensi dan review.

---

## 1. ProductSeeder.php
**Location:** `database/seeders/ProductSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
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

        // Create products with categories
        Product::create([
            'name' => 'Wireless Headphones',
            'slug' => 'wireless-headphones-' . time(),
            'description' => 'Noise-cancelling over-ear headphones with 30-hour battery life.',
            'price' => 129.00,
            'stock' => 25,
            'category_id' => $electronics->id,
        ]);

        // ... more products
    }
}
```

---

## 2. ProductController.php - index() Method
**Location:** `app/Http/Controllers/ProductController.php`

```php
/**
 * Display all products (Homepage for customers)
 */
public function index(Request $request)
{
    $query = Product::query();
    $search = $request->input('search');
    $categoryParam = $request->query('category');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');
    $sortBy = $request->input('sort_by', 'latest');
    
    $categories = \App\Models\Category::orderBy('name')->get();
    $selectedCategory = null;

    // Search filter
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Category filter
    if ($categoryParam) {
        $selectedCategory = \App\Models\Category::where('slug', $categoryParam)
            ->orWhere('id', $categoryParam)
            ->first();

        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory->id);
        }
    }

    // Price filter
    if ($minPrice) {
        $query->where('price', '>=', $minPrice);
    }
    if ($maxPrice) {
        $query->where('price', '<=', $maxPrice);
    }

    // Sort by
    switch ($sortBy) {
        case 'price_asc':
            $query->orderBy('price', 'asc');
            break;
        case 'price_desc':
            $query->orderBy('price', 'desc');
            break;
        case 'name_asc':
            $query->orderBy('name', 'asc');
            break;
        case 'latest':
        default:
            $query->orderBy('created_at', 'desc');
            break;
    }

    $products = $query->paginate(12)->withQueryString();

    $categoryId = $selectedCategory ? $selectedCategory->id : null;
    return view('products.index', compact(
        'products',
        'search',
        'categories',
        'categoryId',
        'categoryParam',
        'minPrice',
        'maxPrice',
        'sortBy'
    ));
}
```

---

## 3. products/index.blade.php - Filter Section
**Location:** `resources/views/products/index.blade.php`

### A. Search & Filter Form

```blade
<!-- Search & Filter Section -->
<div class="mb-8 bg-white rounded-lg shadow-md p-6">
    <form method="GET" action="{{ route('products.index') }}" class="space-y-4">
        <!-- Search Bar -->
        <div class="flex gap-4">
            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search products by name or description..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <button
                type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
            >
                <i class="fas fa-search mr-2"></i>Search
            </button>
            <a href="{{ route('products.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
                Reset All
            </a>
        </div>

        <!-- Filters Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-folder mr-2"></i>Category
                </label>
                <select
                    name="category"
                    id="category"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" @selected($categoryParam === $cat->slug)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Min Price Filter -->
            <div>
                <label for="min_price" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2"></i>Min Price
                </label>
                <input
                    type="number"
                    name="min_price"
                    id="min_price"
                    value="{{ $minPrice ?? '' }}"
                    placeholder="Min price"
                    min="0"
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Max Price Filter -->
            <div>
                <label for="max_price" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2"></i>Max Price
                </label>
                <input
                    type="number"
                    name="max_price"
                    id="max_price"
                    value="{{ $maxPrice ?? '' }}"
                    placeholder="Max price"
                    min="0"
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Sort By -->
            <div>
                <label for="sort_by" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-sort mr-2"></i>Sort By
                </label>
                <select
                    name="sort_by"
                    id="sort_by"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="latest" @selected(($sortBy ?? 'latest') === 'latest')>Latest</option>
                    <option value="price_asc" @selected(($sortBy ?? 'latest') === 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(($sortBy ?? 'latest') === 'price_desc')>Price: High to Low</option>
                    <option value="name_asc" @selected(($sortBy ?? 'latest') === 'name_asc')>Name: A to Z</option>
                </select>
            </div>
        </div>

        <!-- Apply Filters Button -->
        <div class="flex gap-2">
            <button
                type="submit"
                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition flex items-center"
            >
                <i class="fas fa-check mr-2"></i>Apply Filters
            </button>
        </div>
    </form>
</div>
```

### B. Active Filters Display

```blade
<!-- Active Filters Display -->
@php
    $hasActiveFilters = ($search || $categoryParam || $minPrice || $maxPrice || ($sortBy && $sortBy !== 'latest'));
@endphp

@if($hasActiveFilters)
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-700 font-semibold mb-2">Active Filters:</p>
                <div class="flex flex-wrap gap-2">
                    @if($search)
                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-search"></i>Search: "{{ $search }}"
                        </span>
                    @endif
                    @if($categoryParam)
                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-folder"></i>Category: {{ $categoryParam }}
                        </span>
                    @endif
                    @if($minPrice)
                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-dollar-sign"></i>Min: ${{ $minPrice }}
                        </span>
                    @endif
                    @if($maxPrice)
                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-dollar-sign"></i>Max: ${{ $maxPrice }}
                        </span>
                    @endif
                    @if($sortBy && $sortBy !== 'latest')
                        <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-sort"></i>Sort: {{ $sortBy }}
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                Clear All
            </a>
        </div>
    </div>
@endif
```

### C. Product Card with Category Badge

```blade
<div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
    <!-- Product Image -->
    <div class="bg-gray-200 h-48 overflow-hidden relative">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <i class="fas fa-image text-gray-400 text-4xl"></i>
            </div>
        @endif
        @if($product->category)
            <div class="absolute top-2 right-2 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                {{ $product->category->name }}
            </div>
        @endif
    </div>
    
    <!-- Product Info -->
    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->description, 60) }}</p>
        
        <!-- Rating, Price, Stock, Buttons... -->
    </div>
</div>
```

---

## 4. Model Relationships

### Category Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
```

### Product Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    // ... other code ...

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

---

## 5. Testing URLs

```
Base:
http://localhost/products

Filter by Category:
http://localhost/products?category=electronics
http://localhost/products?category=clothing

Filter by Price:
http://localhost/products?min_price=20&max_price=100

Sort:
http://localhost/products?sort_by=price_asc
http://localhost/products?sort_by=price_desc
http://localhost/products?sort_by=name_asc

Combined:
http://localhost/products?category=electronics&min_price=50&max_price=200&sort_by=price_asc
http://localhost/products?search=headphones&category=electronics&sort_by=price_asc
```

---

## 6. Database Queries for Verification

```sql
-- Check categories
SELECT * FROM categories;

-- Check products with categories
SELECT p.id, p.name, p.price, c.name as category 
FROM products p 
LEFT JOIN categories c ON p.category_id = c.id;

-- Count products per category
SELECT c.name, COUNT(p.id) as product_count
FROM categories c
LEFT JOIN products p ON c.id = p.category_id
GROUP BY c.id, c.name;

-- Check price range
SELECT MIN(price) as min_price, MAX(price) as max_price FROM products;
```

---

## 7. Commands untuk Development

```bash
# Fresh database dengan seeder
php artisan migrate:fresh --seed

# Refresh tanpa seeder
php artisan migrate:refresh

# Hanya jalankan seeder
php artisan db:seed

# Jalankan seeder tertentu
php artisan db:seed --class=ProductSeeder

# Cache clear (jika ada masalah)
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 📊 Summary Perubahan

| File | Status | Perubahan |
|------|--------|-----------|
| `database/seeders/ProductSeeder.php` | ✅ Updated | Tambah kategori dan 10 produk dummy |
| `app/Http/Controllers/ProductController.php` | ✅ Updated | Tambah filter logic (price, sort) |
| `resources/views/products/index.blade.php` | ✅ Updated | Tambah filter UI dan kategori badge |
| `app/Models/Category.php` | ✅ Existing | Sudah ada dengan relasi |
| `app/Models/Product.php` | ✅ Existing | Sudah ada dengan relasi |
| `database/migrations/*categories*` | ✅ Existing | Sudah ada |
| `database/migrations/*products*` | ✅ Existing | Sudah ada dengan category_id |

---

**Status: ✅ SEMUA PERUBAHAN SUDAH DILAKUKAN**

Anda bisa langsung menjalankan `php artisan migrate:fresh --seed` dan test di browser! 🚀
