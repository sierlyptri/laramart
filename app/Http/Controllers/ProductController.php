<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display all products (Homepage for customers)
     */
    public function index(Request $request)
    {
        $query = Product::query();
        $search = $request->input('search');
        $categoryParam = $request->query('category'); // expects slug or id
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sortBy = $request->input('sort_by', 'latest'); // latest, price_asc, price_desc
        
        $categories = \App\Models\Category::orderBy('name')->get();
        $selectedCategory = null;

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryParam) {
            // try slug first, fallback to id if numeric
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

        // pass both categories and selected id (views may use either)
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

    /**
     * Show product detail with reviews
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $reviews = $product->reviews()->with('user')->latest()->paginate(5);
        $averageRating = $product->averageRating();

        return view('products.show', compact('product', 'reviews', 'averageRating'));
    }

    /**
     * Admin: Show products list for management
     */
    public function adminIndex(Request $request)
    {
        $query = Product::query();
        $search = $request->input('search');
        $categoryId = $request->input('category');
        $categories = Category::all();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(15);
        return view('admin.products.index', compact('products', 'search', 'categories', 'categoryId'));
    }

    /**
     * Admin: Show create product form
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Admin: Store new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=600,min_height=600,max_width=2000,max_height=2000',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']) . '-' . time();

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Admin: Show edit product form
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Admin: Update product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=600,min_height=600,max_width=2000,max_height=2000',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Admin: Delete product
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
