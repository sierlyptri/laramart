@extends('layouts.app')

@section('title', 'Products - Laramart')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-4">All Products</h1>
    <p class="text-gray-600">Browse our collection of quality products</p>
</div>

<!-- Search Bar -->
<div class="mb-8 bg-white rounded-lg shadow-md p-6">
    <form method="GET" action="{{ route('products.index') }}" class="flex gap-4">
        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Search products by name or description..."
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button
            type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
        >
            <i class="fas fa-search mr-2"></i>Search
        </button>
        @if($search)
            <a href="{{ route('products.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
                Clear
            </a>
        @endif
    </form>
</div>

@if($search)
    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-gray-700">Showing results for: <strong>{{ $search }}</strong></p>
    </div>
@endif

@if($products->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <!-- Product Image -->
                <div class="bg-gray-200 h-48 overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
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
                        <a href="{{ route('products.show', $product->slug) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-center">
                            View Details
                        </a>
                        @if($product->stock > 0 && auth()->check() && auth()->user()->role === 'user')
                            <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
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
        <p class="text-gray-600 text-lg">No products found.</p>
    </div>
@endif
@endsection
