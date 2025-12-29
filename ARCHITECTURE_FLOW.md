# ARSITEKTUR & FLOW: Kategori Produk & Filter Pencarian

## 📐 Database Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      DATABASE SCHEMA                        │
└─────────────────────────────────────────────────────────────┘

┌──────────────────┐                  ┌──────────────────┐
│   CATEGORIES     │                  │    PRODUCTS      │
├──────────────────┤                  ├──────────────────┤
│ id (PK)      ┐   │                  │ id (PK)          │
│ name         │   │                  │ name             │
│ slug         │   │ ──(1:N)──────────│ slug             │
│ created_at   │   │                  │ description      │
│ updated_at   │   │                  │ price            │
└──────────────────┘                  │ stock            │
                                       │ image            │
                                       │ category_id (FK) │
                                       │ created_at       │
                                       │ updated_at       │
                                       └──────────────────┘

Relationship:
- Category hasMany Product
- Product belongsTo Category
```

---

## 🔄 Request Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    USER INTERACTION FLOW                    │
└─────────────────────────────────────────────────────────────┘

User Action:
├─ 1. Open Products Page
│  └─ GET /products
│
├─ 2. Set Filters
│  ├─ Select Category from dropdown
│  ├─ Enter Min Price
│  ├─ Enter Max Price
│  ├─ Select Sort Option
│  └─ Click Apply Filters Button
│
└─ 3. Browser sends GET request
   └─ /products?category=electronics&min_price=50&max_price=200&sort_by=price_asc
```

---

## 🔌 Backend Processing Flow

```
┌─────────────────────────────────────────────────────────────┐
│            PRODUCTCONTROLLER INDEX METHOD FLOW              │
└─────────────────────────────────────────────────────────────┘

Request Received
   │
   ├─ Extract Parameters
   │  ├─ $search
   │  ├─ $categoryParam
   │  ├─ $minPrice
   │  ├─ $maxPrice
   │  └─ $sortBy
   │
   ├─ Build Query
   │  ├─ if ($search) → WHERE name LIKE or description LIKE
   │  │
   │  ├─ if ($categoryParam) → WHERE category_id = ?
   │  │
   │  ├─ if ($minPrice) → WHERE price >= ?
   │  │
   │  ├─ if ($maxPrice) → WHERE price <= ?
   │  │
   │  └─ ORDER BY (based on $sortBy)
   │     ├─ latest: created_at DESC
   │     ├─ price_asc: price ASC
   │     ├─ price_desc: price DESC
   │     └─ name_asc: name ASC
   │
   ├─ Execute Query
   │  └─ paginate(12)->withQueryString()
   │
   └─ Return View with Variables
      ├─ $products
      ├─ $search
      ├─ $categories
      ├─ $minPrice
      ├─ $maxPrice
      ├─ $sortBy
      └─ $categoryId / $categoryParam
```

---

## 🎨 Frontend Rendering Flow

```
┌─────────────────────────────────────────────────────────────┐
│          BLADE VIEW RENDERING FLOW                          │
└─────────────────────────────────────────────────────────────┘

Load View: products/index.blade.php
   │
   ├─ Render Filter Section
   │  ├─ Load Categories from $categories
   │  │  └─ foreach($categories as $cat)
   │  │     └─ <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
   │  │
   │  ├─ Display Current Filter Values
   │  │  ├─ value="{{ $minPrice ?? '' }}"
   │  │  ├─ value="{{ $maxPrice ?? '' }}"
   │  │  ├─ @selected($categoryParam === $cat->slug)
   │  │  └─ @selected(($sortBy ?? 'latest') === 'price_asc')
   │  │
   │  └─ Create Form
   │     └─ method="GET" action="{{ route('products.index') }}"
   │
   ├─ Display Active Filters (if any)
   │  ├─ Check if $search exists → show badge
   │  ├─ Check if $categoryParam exists → show badge
   │  ├─ Check if $minPrice exists → show badge
   │  ├─ Check if $maxPrice exists → show badge
   │  └─ Check if $sortBy exists → show badge
   │
   ├─ Render Product Cards
   │  ├─ foreach($products as $product)
   │  │  ├─ Display Image
   │  │  ├─ Display Category Badge ({{ $product->category->name }})
   │  │  ├─ Display Name, Description
   │  │  ├─ Display Price, Stock Status
   │  │  ├─ Display Rating & Reviews
   │  │  └─ Display Action Buttons
   │  │
   │  └─ Display Products Count
   │     └─ "Showing X product(s) out of Y total"
   │
   └─ Display Pagination Links
      └─ {{ $products->links() }}
```

---

## 🔗 URL Parameter Mapping

```
┌─────────────────────────────────────────────────────────────┐
│            URL QUERY STRING ↔ CONTROLLER MAPPING            │
└─────────────────────────────────────────────────────────────┘

URL: /products?category=electronics&min_price=50&max_price=200&sort_by=price_asc

↓ Request arrives at ProductController@index

$request->query('category')     → 'electronics'    → Filter by category
$request->input('min_price')    → '50'             → Filter price >= 50
$request->input('max_price')    → '200'            → Filter price <= 200
$request->input('sort_by')      → 'price_asc'      → Sort price ascending
$request->input('search')       → null             → No search filter

↓ Build database query

SELECT * FROM products
WHERE category_id = (SELECT id FROM categories WHERE slug = 'electronics')
  AND price >= 50
  AND price <= 200
ORDER BY price ASC
LIMIT 12 OFFSET 0

↓ Return results with pagination and query string
```

---

## 📝 Data Flow Example

```
┌─────────────────────────────────────────────────────────────┐
│              COMPLETE REQUEST/RESPONSE CYCLE                │
└─────────────────────────────────────────────────────────────┘

STEP 1: USER INTERACTION
┌────────────────────────────────────────────────────────────┐
│ User opens http://localhost/products                       │
│ - All products displayed                                   │
│ - All categories in dropdown                               │
│ - Price inputs empty                                       │
│ - Sort by = 'latest' (default)                            │
└────────────────────────────────────────────────────────────┘
                            ↓
STEP 2: USER FILTERS
┌────────────────────────────────────────────────────────────┐
│ Category: Electronics                                      │
│ Min Price: 50                                              │
│ Max Price: 200                                             │
│ Sort: Price Ascending                                      │
│ Click "Apply Filters"                                      │
└────────────────────────────────────────────────────────────┘
                            ↓
STEP 3: BROWSER SUBMITS FORM
┌────────────────────────────────────────────────────────────┐
│ GET /products?category=electronics&min_price=50&          │
│ max_price=200&sort_by=price_asc HTTP/1.1                 │
└────────────────────────────────────────────────────────────┘
                            ↓
STEP 4: LARAVEL ROUTING
┌────────────────────────────────────────────────────────────┐
│ Route: GET /products → ProductController@index            │
└────────────────────────────────────────────────────────────┘
                            ↓
STEP 5: CONTROLLER PROCESSING
┌────────────────────────────────────────────────────────────┐
│ $categoryParam = 'electronics'                            │
│ $minPrice = '50'                                          │
│ $maxPrice = '200'                                         │
│ $sortBy = 'price_asc'                                    │
│                                                           │
│ Query Builder:                                            │
│ SELECT * FROM products                                    │
│ WHERE category_id IN (                                    │
│   SELECT id FROM categories WHERE slug = 'electronics'   │
│ )                                                         │
│ AND price >= 50                                           │
│ AND price <= 200                                          │
│ ORDER BY price ASC                                        │
│ LIMIT 12                                                  │
└────────────────────────────────────────────────────────────┘
                            ↓
STEP 6: DATABASE QUERY
┌────────────────────────────────────────────────────────────┐
│ Database returns 8 products:                              │
│ - USB-C Cable ($12.99)                                   │
│ - Water Bottle ($24.50)                                  │
│ - Smartwatch ($199.99)                                   │
│ - ... (sorted by price asc)                              │
└────────────────────────────────────────────────────────────┘
                            ↓
STEP 7: VIEW RENDERING
┌────────────────────────────────────────────────────────────┐
│ Blade template receives:                                  │
│ - $products (8 items, paginated)                         │
│ - $categories (all 4 categories)                         │
│ - $categoryParam = 'electronics'                         │
│ - $minPrice = '50'                                       │
│ - $maxPrice = '200'                                      │
│ - $sortBy = 'price_asc'                                  │
│                                                           │
│ Filter form populates with:                              │
│ - Category dropdown: 'electronics' selected              │
│ - Min Price input: value="50"                            │
│ - Max Price input: value="200"                           │
│ - Sort dropdown: 'price_asc' selected                    │
│                                                           │
│ Active Filters section shows:                            │
│ [Category: electronics] [Min: $50] [Max: $200]           │
│ [Sort: price_asc] [Clear All]                           │
│                                                           │
│ Product Cards displayed with:                            │
│ - Category badge showing "Electronics"                   │
│ - Price range $12.99 to $199.99                         │
│ - All sorted ascending by price                          │
└────────────────────────────────────────────────────────────┘
                            ↓
STEP 8: RESPONSE SENT TO BROWSER
┌────────────────────────────────────────────────────────────┐
│ HTTP/1.1 200 OK                                           │
│ Content-Type: text/html                                  │
│                                                           │
│ [HTML Page with filtered products and active filters]    │
└────────────────────────────────────────────────────────────┘
                            ↓
STEP 9: USER SEES RESULTS
┌────────────────────────────────────────────────────────────┐
│ Page displays:                                            │
│ - Filter section with selected values                    │
│ - Active filters badges                                  │
│ - 8 electronics products sorted by price (low to high)   │
│ - "Showing 8 product(s) out of 50 total"                │
│ - Pagination links (showing page 1 of 1)                │
└────────────────────────────────────────────────────────────┘
```

---

## 🎯 Component Relationships

```
┌─────────────────────────────────────────────────────────────┐
│                    COMPONENT HIERARCHY                      │
└─────────────────────────────────────────────────────────────┘

ProductController
    │
    ├─ Queries: Product, Category
    │
    ├─ Returns View: products.index
    │   │
    │   ├─ Component: Filter Form
    │   │  ├─ Category Dropdown (from $categories)
    │   │  ├─ Min Price Input
    │   │  ├─ Max Price Input
    │   │  ├─ Sort By Dropdown
    │   │  └─ Apply Filters Button
    │   │
    │   ├─ Component: Active Filters Display
    │   │  ├─ Search Badge
    │   │  ├─ Category Badge
    │   │  ├─ Min Price Badge
    │   │  ├─ Max Price Badge
    │   │  ├─ Sort Badge
    │   │  └─ Clear All Button
    │   │
    │   ├─ Component: Results Info
    │   │  └─ Showing X of Y products
    │   │
    │   ├─ Component: Products Grid
    │   │  └─ Product Card (repeated)
    │   │     ├─ Image with Category Badge
    │   │     ├─ Name & Description
    │   │     ├─ Rating
    │   │     ├─ Price & Stock Status
    │   │     └─ Action Buttons
    │   │
    │   └─ Component: Pagination
    │      └─ Page Links with Query String Preservation
```

---

## 🔐 Data Persistence

```
┌─────────────────────────────────────────────────────────────┐
│            QUERY STRING PERSISTENCE MECHANISM              │
└─────────────────────────────────────────────────────────────┘

Original URL:
/products?category=electronics&min_price=50&max_price=200&sort_by=price_asc

User clicks page 2 pagination link:
    ↓
Laravel's withQueryString() preserves all query parameters:
/products?category=electronics&min_price=50&max_price=200&sort_by=price_asc&page=2

User clicks Apply Filters again from page 2:
    ↓
Form resubmits with same parameters:
/products?category=electronics&min_price=50&max_price=200&sort_by=price_asc

⚠️ Important:
- withQueryString() ensures pagination links keep all filters
- Form method="GET" preserves all filter values in URL
- Hidden inputs not needed - form fields use name attributes
```

---

## 🧪 Testing Scenarios

```
┌─────────────────────────────────────────────────────────────┐
│              FUNCTIONAL TESTING SCENARIOS                   │
└─────────────────────────────────────────────────────────────┘

✅ Scenario 1: Filter by Category Only
─────────────────────────────────────────
1. Open /products
2. Select "Electronics" from category dropdown
3. Click "Apply Filters"
4. Verify: Only electronics products shown
5. Verify: Category badge shows on cards
6. Verify: Active filter badge shows "Category: electronics"

✅ Scenario 2: Filter by Price Range
─────────────────────────────────────────
1. Open /products
2. Enter Min: 20, Max: 100
3. Click "Apply Filters"
4. Verify: Only products with price 20-100 shown
5. Verify: Active filter badges show "Min: $20" and "Max: $100"

✅ Scenario 3: Combined Filters
─────────────────────────────────────────
1. Open /products
2. Select "Clothing" category
3. Enter Min: 30, Max: 80
4. Select "Price: Low to High" sort
5. Click "Apply Filters"
6. Verify: Only clothing 30-80, sorted ascending
7. Verify: All 4 active filter badges displayed

✅ Scenario 4: Search + Filter
─────────────────────────────────────────
1. Open /products
2. Enter search: "shirt"
3. Select category: "Clothing"
4. Click "Apply Filters"
5. Verify: Clothing products with "shirt" in name/description
6. Verify: Category badge + Search badge both shown

✅ Scenario 5: Clear Filters
─────────────────────────────────────────
1. Apply filters as in Scenario 3
2. Click "Clear All" button
3. Verify: All filters cleared
4. Verify: URL reset to /products
5. Verify: All products shown
6. Verify: No active filter badges shown

✅ Scenario 6: Pagination with Filters
─────────────────────────────────────────
1. Apply filter: category=electronics
2. Click page 2 pagination link
3. Verify: URL includes ?category=electronics&page=2
4. Verify: Filter still applied on page 2
5. Click another product, go back
6. Verify: You're back on filtered page 2
```

---

## 📊 Filter Logic Truth Table

```
┌──────────────┬──────────────┬──────────┬──────────┬──────────┬──────────────────────┐
│   Search     │   Category   │ MinPrice │ MaxPrice │ SortBy   │ Results              │
├──────────────┼──────────────┼──────────┼──────────┼──────────┼──────────────────────┤
│ empty        │ empty        │ empty    │ empty    │ latest   │ All products, newest │
│              │              │          │          │          │ first                │
├──────────────┼──────────────┼──────────┼──────────┼──────────┼──────────────────────┤
│ 'shirt'      │ empty        │ empty    │ empty    │ latest   │ Shirt products,      │
│              │              │          │          │          │ newest first         │
├──────────────┼──────────────┼──────────┼──────────┼──────────┼──────────────────────┤
│ empty        │ electronics  │ empty    │ empty    │ latest   │ Electronics only,    │
│              │              │          │          │          │ newest first         │
├──────────────┼──────────────┼──────────┼──────────┼──────────┼──────────────────────┤
│ empty        │ empty        │ 50       │ empty    │ latest   │ Products >= $50,     │
│              │              │          │          │          │ newest first         │
├──────────────┼──────────────┼──────────┼──────────┼──────────┼──────────────────────┤
│ empty        │ empty        │ 50       │ 100      │ latest   │ Products $50-100,    │
│              │              │          │          │          │ newest first         │
├──────────────┼──────────────┼──────────┼──────────┼──────────┼──────────────────────┤
│ empty        │ empty        │ empty    │ empty    │price_asc │ All products,        │
│              │              │          │          │          │ cheapest first       │
├──────────────┼──────────────┼──────────┼──────────┼──────────┼──────────────────────┤
│'headphone'   │ electronics  │ 100      │ 200      │price_asc │ Electronics          │
│              │              │          │          │          │ headphones $100-200  │
│              │              │          │          │          │ sorted by price      │
└──────────────┴──────────────┴──────────┴──────────┴──────────┴──────────────────────┘
```

---

**Diagram Complete! 📊**

Semua flow dan arsitektur sudah tergambar. Silakan refer ke diagram ini untuk memahami alur sistem.
