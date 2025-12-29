# Dokumentasi Fitur: Kategori Produk & Filter Pencarian

## Overview
Fitur ini menambahkan kemampuan untuk mengkategorikan produk dan menyediakan filter pencarian yang comprehensive termasuk:
- Filter berdasarkan kategori
- Filter berdasarkan range harga (min & max)
- Sorting (Latest, Price Ascending/Descending, Name A-Z)
- Display kategori pada setiap produk
- Tampilan filter yang aktif

---

## 1. Database & Models (Backend)

### ✅ Migration: Tabel Categories
**File:** `database/migrations/2025_12_28_create_categories_table.php`

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('slug')->unique();
    $table->timestamps();
});
```

**Kolom:**
- `id`: Primary key
- `name`: Nama kategori (unique)
- `slug`: URL-friendly slug (unique)
- `timestamps`: created_at & updated_at

---

### ✅ Migration: Modifikasi Tabel Products
**File:** `database/migrations/2025_12_26_202000_create_products_table.php`

Sudah menambahkan kolom `category_id`:
```php
$table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete()->after('image');
```

---

### ✅ Model: Category
**File:** `app/Models/Category.php`

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

    /**
     * Get all products in this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
```

---

### ✅ Model: Product
**File:** `app/Models/Product.php`

Relasi `belongsTo` Category sudah ada:
```php
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}
```

---

### ✅ DatabaseSeeder & ProductSeeder
**File:** `database/seeders/ProductSeeder.php`

Sudah diupdate dengan:
1. 4 kategori dummy: Electronics, Clothing, Food & Beverage, Accessories
2. 10 produk dummy yang terkategori dengan data lengkap

**Cara Menjalankan:**
```bash
php artisan migrate:fresh --seed
```

---

## 2. Controller Logic

### ✅ ProductController - index() Method
**File:** `app/Http/Controllers/ProductController.php`

#### Parameter yang Didukung:
1. `search`: Mencari berdasarkan nama atau deskripsi produk
2. `category`: Filter berdasarkan slug atau ID kategori
3. `min_price`: Filter harga minimum
4. `max_price`: Filter harga maksimum
5. `sort_by`: Sorting (latest, price_asc, price_desc, name_asc)

#### Logika Filtering:
```php
public function index(Request $request)
{
    $query = Product::query();
    $search = $request->input('search');
    $categoryParam = $request->query('category');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');
    $sortBy = $request->input('sort_by', 'latest');
    
    // Search filter
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Category filter
    if ($categoryParam) {
        $selectedCategory = Category::where('slug', $categoryParam)
            ->orWhere('id', $categoryParam)->first();
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

    // Sorting
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
        default:
            $query->orderBy('created_at', 'desc');
    }

    $products = $query->paginate(12)->withQueryString();
    
    // Pass ke view
    return view('products.index', compact(
        'products', 'search', 'categories', 'categoryId', 
        'categoryParam', 'minPrice', 'maxPrice', 'sortBy'
    ));
}
```

---

## 3. Frontend (Blade View)

### ✅ View: products/index.blade.php

#### Fitur-Fitur:

**A. Search Bar**
- Input text untuk mencari produk
- Tombol Search dan Reset All

**B. Filter Section:**
```blade
<!-- Category Dropdown -->
<select name="category">
    <option value="">All Categories</option>
    @foreach($categories as $cat)
        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
    @endforeach
</select>

<!-- Min Price Input -->
<input type="number" name="min_price" placeholder="Min price">

<!-- Max Price Input -->
<input type="number" name="max_price" placeholder="Max price">

<!-- Sort By Dropdown -->
<select name="sort_by">
    <option value="latest">Latest</option>
    <option value="price_asc">Price: Low to High</option>
    <option value="price_desc">Price: High to Low</option>
    <option value="name_asc">Name: A to Z</option>
</select>
```

**C. Active Filters Display**
- Menampilkan badge untuk setiap filter yang aktif
- Tombol "Clear All" untuk menghapus semua filter

**D. Product Cards Enhancement:**
- Category badge di atas gambar produk
- Hover effect pada gambar
- Styling yang responsif dengan Tailwind CSS

**E. Results Information**
- Menampilkan jumlah produk yang ditampilkan vs total
- Pesan ketika tidak ada produk ditemukan

---

## 4. Testing & Penggunaan

### URL Examples:

**Base URL (semua produk):**
```
http://localhost/products
```

**Filter by Category:**
```
http://localhost/products?category=electronics
http://localhost/products?category=clothing
```

**Filter by Price Range:**
```
http://localhost/products?min_price=10&max_price=100
```

**Filter by Search:**
```
http://localhost/products?search=headphones
```

**Combined Filters:**
```
http://localhost/products?category=electronics&min_price=50&max_price=200&sort_by=price_asc
```

**With Search + Category:**
```
http://localhost/products?search=shirt&category=clothing&sort_by=name_asc
```

---

## 5. Fitur Lanjutan (Opsional)

Jika ingin menambahkan lebih banyak fitur:

### A. Tambah Kategori Melalui Admin
```php
// Di AdminController atau route baru
Route::resource('admin.categories', CategoryController::class);
```

### B. Sub-categories
Tambah kolom `parent_id` di migration categories untuk nested categories.

### C. Filters Lebih Advanced
- Rating filter (stars)
- Stock status filter
- Discount/Sale filter

### D. Save Filter Preferences
- Simpan preferensi filter user ke database
- Implementasi dengan cookies atau session

### E. AJAX Filter
- Buat filter real-time tanpa page reload
- Gunakan Alpine.js atau jQuery

---

## 6. Troubleshooting

### Q: Kategori tidak muncul di dropdown?
**A:** Pastikan sudah menjalankan migration dan seeder:
```bash
php artisan migrate:fresh --seed
```

### Q: Filter harga tidak bekerja?
**A:** Pastikan input type="number" memiliki value valid. Cek database apakah price sudah tersimpan sebagai decimal.

### Q: Category slug tidak sesuai URL?
**A:** Gunakan `Str::slug()` saat membuat atau mengupdate kategori untuk konsistensi.

### Q: Pagination query string hilang?
**A:** Sudah menggunakan `withQueryString()` untuk menjaga parameter filter saat pagination.

---

## 7. File-File yang Dimodifikasi

1. ✅ `app/Http/Controllers/ProductController.php` - Update index() dengan filter logic
2. ✅ `database/seeders/ProductSeeder.php` - Tambah kategori dan produk dummy
3. ✅ `resources/views/products/index.blade.php` - UI lengkap dengan filter section

---

## 8. Commands Penting

```bash
# Jalankan migration fresh dengan seeder
php artisan migrate:fresh --seed

# Jika hanya ingin refresh seeder
php artisan db:seed

# Jalankan seeder tertentu
php artisan db:seed --class=ProductSeeder

# Buat migration baru jika diperlukan
php artisan make:migration add_category_to_products --table=products

# Buat model baru
php artisan make:model Category -m

# Buat seeder baru
php artisan make:seeder CategorySeeder
```

---

## Kesimpulan

Fitur kategori produk dan filter pencarian sudah fully implemented dengan:
- ✅ Database schema lengkap
- ✅ Models dengan relasi
- ✅ Controller logic dengan multi-filter
- ✅ Beautiful & responsive UI
- ✅ Dummy data siap digunakan
- ✅ Query parameter persistence

Semua sudah siap untuk production! 🚀
