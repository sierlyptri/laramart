<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $products = $query->paginate(12);
        return view('products.index', compact('products', 'search'));
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

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $products = $query->paginate(15);
        return view('admin.products.index', compact('products', 'search'));
    }

    /**
     * Admin: Show create product form
     */
    public function create()
    {
        return view('admin.products.create');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        return view('admin.products.edit', compact('product'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
