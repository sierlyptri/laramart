# CARA MENJALANKAN & TESTING: Kategori & Filter Pencarian

## 🚀 QUICK START (5 MENIT)

### Step 1: Database Fresh & Seed
```bash
# Buka Terminal PowerShell di folder project
# Lokasi: c:\xampp\htdocs\laramart

php artisan migrate:fresh --seed
```

**Expected Output:**
```
Migrating: 0001_01_01_000000_create_users_table
Migrating: 0001_01_01_000001_create_cache_table
...
Seeding: Database\Seeders\UserSeeder
Seeding: Database\Seeders\ProductSeeder
Database seeding completed successfully.
```

### Step 2: Start Laravel Development Server
```bash
php artisan serve
```

**Output:**
```
Laravel development server started at http://127.0.0.1:8000
```

### Step 3: Open in Browser
```
http://localhost:8000/products
```

---

## ✅ TESTING CHECKLIST

### A. Basic Display Test

**✓ Test 1: Page Loads Without Filters**
- [ ] Navigate to `/products`
- [ ] See all products displayed
- [ ] See all categories in dropdown
- [ ] See 4 empty filter inputs
- [ ] See sorting set to "Latest"
- [ ] No active filters badge shown

**✓ Test 2: Filter Section Renders Correctly**
- [ ] Category dropdown shows all 4 categories
  - [ ] Electronics
  - [ ] Clothing
  - [ ] Food & Beverage
  - [ ] Accessories
- [ ] Min Price input exists and is empty
- [ ] Max Price input exists and is empty
- [ ] Sort By dropdown shows 4 options
- [ ] "Apply Filters" button visible
- [ ] "Reset All" button visible

**✓ Test 3: Products Display**
- [ ] Products grid shows 12 products per page
- [ ] Each product card shows:
  - [ ] Product image
  - [ ] Category badge (top-right)
  - [ ] Product name
  - [ ] Description (truncated)
  - [ ] Rating with stars
  - [ ] Price formatted with $
  - [ ] Stock status (In Stock / Out of Stock)
  - [ ] "View Details" button
  - [ ] "Add to Cart" button (if logged in & in stock)

---

### B. Category Filter Test

**✓ Test 4: Filter by Electronics**
1. Select "Electronics" from dropdown
2. Click "Apply Filters"
3. [ ] URL changes to `/products?category=electronics`
4. [ ] Only 4 products shown (Wireless Headphones, USB-C Cable, Smartwatch, Water Bottle)
5. [ ] Active filter badge shows "Category: electronics"
6. [ ] All product cards have "Electronics" badge
7. [ ] Pagination shows correct count

**✓ Test 5: Filter by Clothing**
1. Select "Clothing" from dropdown
2. Click "Apply Filters"
3. [ ] URL changes to `/products?category=clothing`
4. [ ] Only 3 products shown (Sample T-Shirt, Blue Denim Jeans, Winter Jacket)
5. [ ] Category badge shows "Clothing"
6. [ ] Active filter badge shows "Category: clothing"

**✓ Test 6: Filter by Food & Beverage**
1. Select "Food & Beverage" from dropdown
2. Click "Apply Filters"
3. [ ] Only 2 products shown (Premium Coffee Beans, Organic Tea Set)
4. [ ] Category badge shows "Food & Beverage"

**✓ Test 7: Filter by Accessories**
1. Select "Accessories" from dropdown
2. Click "Apply Filters"
3. [ ] Only 2 products shown (Water Bottle, Leather Wallet)
4. [ ] Category badge shows "Accessories"

---

### C. Price Range Filter Test

**✓ Test 8: Filter by Min Price Only**
1. Enter Min Price: `50`
2. Click "Apply Filters"
3. [ ] URL shows `?min_price=50`
4. [ ] Only products >= $50 shown
5. [ ] Active filter badge shows "Min: $50"
6. [ ] Min Price input still shows "50"

**✓ Test 9: Filter by Max Price Only**
1. Clear filters first
2. Enter Max Price: `100`
3. Click "Apply Filters"
4. [ ] URL shows `?max_price=100`
5. [ ] Only products <= $100 shown
6. [ ] Active filter badge shows "Max: $100"
7. [ ] Max Price input still shows "100"

**✓ Test 10: Filter by Price Range (Min + Max)**
1. Enter Min Price: `20`
2. Enter Max Price: `100`
3. Click "Apply Filters"
4. [ ] URL shows `?min_price=20&max_price=100`
5. [ ] Only products between $20-$100 shown
6. [ ] Both badges shown: "Min: $20" and "Max: $100"
7. [ ] Correct products filtered:
   - [ ] Sample T-Shirt ($19.99) - NOT shown (< $20)
   - [ ] Water Bottle ($24.50) - SHOWN
   - [ ] USB-C Cable ($12.99) - NOT shown (< $20)
   - [ ] Blue Denim Jeans ($49.99) - SHOWN
   - [ ] Premium Coffee Beans ($34.99) - SHOWN
   - [ ] Organic Tea Set ($28.50) - SHOWN
   - [ ] Smartwatch ($199.99) - NOT shown (> $100)
   - [ ] etc.

---

### D. Sort Filter Test

**✓ Test 11: Sort Latest (Default)**
1. Clear all filters
2. Select "Latest" (default)
3. Click "Apply Filters"
4. [ ] Products sorted by created_at DESC
5. [ ] Newest products first

**✓ Test 12: Sort Price: Low to High**
1. Select "Price: Low to High"
2. Click "Apply Filters"
3. [ ] URL shows `?sort_by=price_asc`
4. [ ] Active filter shows "Sort: price_asc"
5. [ ] Products ordered by price ascending:
   - [ ] 1st: USB-C Cable ($12.99)
   - [ ] 2nd: Water Bottle ($24.50)
   - [ ] 3rd: Organic Tea Set ($28.50)
   - [ ] 4th: Premium Coffee Beans ($34.99)
   - [ ] etc.

**✓ Test 13: Sort Price: High to Low**
1. Select "Price: High to Low"
2. Click "Apply Filters"
3. [ ] Products ordered by price descending:
   - [ ] 1st: Smartwatch ($199.99)
   - [ ] 2nd: Winter Jacket ($89.99)
   - [ ] 3rd: Wireless Headphones ($129.00)
   - [ ] etc.

**✓ Test 14: Sort Name A to Z**
1. Select "Name: A to Z"
2. Click "Apply Filters"
3. [ ] Products sorted alphabetically by name
4. [ ] 1st: Blue Denim Jeans
5. [ ] etc.

---

### E. Combined Filter Test

**✓ Test 15: Category + Price**
1. Select Category: "Electronics"
2. Enter Min Price: "50"
3. Enter Max Price: "200"
4. Click "Apply Filters"
5. [ ] URL: `/products?category=electronics&min_price=50&max_price=200`
6. [ ] Only Electronics products between $50-$200 shown
7. [ ] All 3 filter badges shown
8. Products shown:
   - [ ] Wireless Headphones ($129.00) ✓
   - [ ] USB-C Cable ($12.99) ✗
   - [ ] Smartwatch ($199.99) ✓

**✓ Test 16: Category + Price + Sort**
1. Select Category: "Clothing"
2. Enter Min Price: "20"
3. Enter Max Price: "100"
4. Select Sort: "Price: Low to High"
5. Click "Apply Filters"
6. [ ] URL has all parameters
7. [ ] Only Clothing $20-100 shown, sorted by price asc
8. Products order:
   - [ ] 1st: Sample T-Shirt ($19.99) - Wait, this is < $20, should NOT show
   - Actually: Blue Denim Jeans ($49.99), Winter Jacket ($89.99)

---

### F. Search Filter Test

**✓ Test 17: Search Only**
1. Type "headphones" in search box
2. Click "Search" button
3. [ ] URL shows `/products?search=headphones`
4. [ ] Active filter shows "Search: 'headphones'"
5. [ ] Only "Wireless Headphones" shown

**✓ Test 18: Search + Category**
1. Type "shirt" in search box
2. Select Category: "Clothing"
3. Click "Apply Filters"
4. [ ] URL: `/products?search=shirt&category=clothing`
5. [ ] Only clothing items with "shirt" in name/desc shown
6. [ ] Products shown: Sample T-Shirt

**✓ Test 19: Search + Category + Price + Sort**
1. Type "shirt" in search
2. Select Category: "Clothing"
3. Min Price: 10, Max Price: 50
4. Sort: Price Ascending
5. Click "Apply Filters"
6. [ ] URL has all parameters
7. [ ] Correct results filtered and sorted

---

### G. Active Filters Display Test

**✓ Test 20: Active Filters Badges**
1. Apply filters: Category=Electronics, Min=50, Sort=price_asc
2. [ ] Blue badges show at top with icons:
   - [ ] 🏷️ Category: electronics
   - [ ] 💲 Min: $50
   - [ ] ➡️ Sort: price_asc
3. [ ] Each badge styled consistently
4. [ ] "Clear All" link visible on right

**✓ Test 21: Active Filters Update**
1. Apply filters as above
2. [ ] Badges show
3. Add Max Price: 150
4. Click "Apply Filters" again
5. [ ] New badge appears: "Max: $150"
6. [ ] All 4 badges visible

**✓ Test 22: Clear All Button**
1. Apply multiple filters
2. Click "Clear All" link
3. [ ] All filters cleared
4. [ ] URL resets to `/products`
5. [ ] All products shown
6. [ ] No active filter badges

---

### H. Reset Button Test

**✓ Test 23: Reset All Button in Filter Section**
1. Apply filters
2. Click "Reset All" button (gray button)
3. [ ] URL clears
4. [ ] All products shown
5. [ ] Filter inputs reset to empty
6. [ ] No active badges

---

### I. Pagination Test

**✓ Test 24: Pagination with Filters**
1. Filter to show multiple pages
2. Click on page 2 link
3. [ ] URL maintains filter parameters: `/products?category=X&page=2`
4. [ ] Filter still applied on page 2
5. [ ] Correct products shown
6. [ ] Can navigate between pages while keeping filters

**✓ Test 25: Pagination without Filters**
1. Clear all filters
2. See pagination links (should show multiple pages)
3. Click page 2
4. [ ] Products on page 2 shown
5. [ ] Different products than page 1

---

### J. Responsive Design Test

**✓ Test 26: Mobile View (< 640px)**
```
Desktop browser Dev Tools: Toggle device toolbar to mobile
```
- [ ] Filter section stacks vertically
- [ ] Each input takes full width
- [ ] Buttons stack properly
- [ ] Product grid shows 1 column
- [ ] No overflow or scroll issues
- [ ] Text readable
- [ ] Inputs accessible

**✓ Test 27: Tablet View (640px - 1024px)**
- [ ] Filter section shows 2 columns
- [ ] Product grid shows 2 columns
- [ ] All readable and accessible

**✓ Test 28: Desktop View (> 1024px)**
- [ ] Filter section shows 4 columns (ideal)
- [ ] Product grid shows 4 columns
- [ ] Spacing looks good
- [ ] Hover effects work (shadow on cards)

---

### K. Database Verification Test

**✓ Test 29: Check Database**

```bash
# Buka terminal dan jalankan:
php artisan tinker
```

```php
# Di Tinker, jalankan:
>>> App\Models\Category::all()
=> Illuminate\Database\Eloquent\Collection {#9485
     all: [
       App\Models\Category {#9486
         id: 1,
         name: "Electronics",
         slug: "electronics",
         ...
       },
       ...
     ]
   }

>>> App\Models\Product::with('category')->limit(3)->get()
=> Illuminate\Database\Eloquent\Collection {
     all: [
       App\Models\Product {
         id: 1,
         name: "Wireless Headphones",
         category_id: 1,
         category: App\Models\Category {...}
       },
       ...
     ]
   }

>>> quit
```

Expected:
- [ ] 4 categories exist
- [ ] 10 products exist
- [ ] Each product has category_id set
- [ ] category relationship works

---

## 🐛 TROUBLESHOOTING

### Issue 1: Products not showing
**Solution:**
```bash
php artisan migrate:fresh --seed
```

### Issue 2: Categories dropdown empty
**Solution:**
1. Check database: Are categories created?
```bash
php artisan tinker
>>> App\Models\Category::count()
# Should return 4
```

2. If empty, seed again:
```bash
php artisan db:seed --class=ProductSeeder
```

### Issue 3: Filter not working
**Check:**
- [ ] Column name typo
- [ ] Database schema correct
- [ ] No PHP errors in log

```bash
# Check logs
tail -f storage/logs/laravel.log
```

### Issue 4: Category badge not showing on cards
**Check in view:**
- [ ] Product has category loaded
- [ ] Try: `{{ $product->category?->name ?? 'No Category' }}`

### Issue 5: withQueryString() not preserving parameters
**Check:**
- [ ] Using correct method: `->withQueryString()`
- [ ] Not: `->links('pagination::bootstrap-4')`
- [ ] Use: `->links()`

---

## 📊 SAMPLE TEST DATA

### Categories Created
```
1. Electronics
2. Clothing  
3. Food & Beverage
4. Accessories
```

### Products Created (10 total)
```
Electronics (4):
- Wireless Headphones - $129.00
- USB-C Charging Cable - $12.99
- Smartwatch - $199.99

Clothing (3):
- Sample T-Shirt - $19.99
- Blue Denim Jeans - $49.99
- Winter Jacket - $89.99

Food & Beverage (2):
- Premium Coffee Beans - $34.99
- Organic Tea Set - $28.50

Accessories (2):
- Water Bottle - $24.50
- Leather Wallet - $44.99
```

---

## 🎯 SUCCESS CRITERIA

**All of the following should work:**

✅ Page loads with all products  
✅ Categories dropdown shows 4 options  
✅ Can filter by category  
✅ Can filter by price (min/max)  
✅ Can sort by price, name, latest  
✅ Can search for products  
✅ Can combine filters  
✅ Active filters show as badges  
✅ Clear All button works  
✅ Pagination maintains filters  
✅ Mobile responsive  
✅ Category badge shows on cards  
✅ No JavaScript errors  
✅ No database errors  

---

## 📱 TEST URLS

Salin dan paste di browser untuk test:

```
# Base - all products
http://localhost:8000/products

# Filter by category
http://localhost:8000/products?category=electronics
http://localhost:8000/products?category=clothing

# Filter by price
http://localhost:8000/products?min_price=20&max_price=100

# Sort
http://localhost:8000/products?sort_by=price_asc
http://localhost:8000/products?sort_by=price_desc

# Combined
http://localhost:8000/products?category=electronics&min_price=50&max_price=200&sort_by=price_asc

# With search
http://localhost:8000/products?search=headphones&category=electronics

# Multiple filters
http://localhost:8000/products?search=shirt&category=clothing&min_price=10&max_price=60&sort_by=price_asc
```

---

## ✨ TESTING COMPLETE!

Jika semua test passes, fitur Kategori & Filter Pencarian sudah fully functional! 🎉

**Status: READY FOR PRODUCTION** ✅
