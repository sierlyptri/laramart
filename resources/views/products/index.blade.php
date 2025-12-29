@extends('layouts.app')

@section('title', 'Products - Laramart')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-4">All Products</h1>
    <p class="text-gray-600">Browse our collection of quality products</p>
</div>

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

<!-- Products Grid -->
@if($products->count() > 0)
    <div class="mb-4 text-gray-600">
        <p>Showing <strong>{{ $products->count() }}</strong> product(s) out of <strong>{{ $products->total() }}</strong> total</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($products as $product)
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

                    <!-- Rating -->
                    @php
                        $avgRating = $product->averageRating();
                        $reviewCount = $product->reviews()->count();
                    @endphp
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avgRating ?? 0))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-600 text-sm ml-2">({{ $reviewCount }})</span>
                    </div>

                    <!-- Price & Stock -->
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                        <span class="text-sm @if($product->stock > 0) text-green-600 @else text-red-600 @endif">
                            {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                        </span>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-center transition">
                            View Details
                        </a>
                        @if($product->stock > 0 && auth()->check() && auth()->user()->role === 'user')
                            <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        {{ $products->links() }}
    </div>
@else
    <div class="text-center py-12">
        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
        <p class="text-gray-600 text-lg">No products found matching your filters.</p>
        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 mt-4 inline-block">
            <i class="fas fa-redo mr-2"></i>Clear filters and try again
        </a>
    </div>
@endif
@endsection
