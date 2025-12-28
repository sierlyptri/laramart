@extends('layouts.app')

@section('title', 'Laramart - Home')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-lg p-12 mb-12">
    <h1 class="text-5xl font-bold mb-4">Welcome to Laramart</h1>
    <p class="text-xl mb-6">Discover amazing products at unbeatable prices!</p>
    <a href="{{ route('products.index') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 inline-block">
        Shop Now
    </a>
</div>

<!-- Featured Products -->
<section>
    <h2 class="text-3xl font-bold mb-8">Featured Products</h2>
    
    @if($featuredProducts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
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
                                View
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
    @else
        <div class="text-center py-12">
            <p class="text-gray-600 text-lg">No featured products available at the moment.</p>
        </div>
    @endif
</section>

<!-- Why Choose Us Section -->
<section class="mt-16">
    <h2 class="text-3xl font-bold mb-8 text-center">Why Choose Laramart?</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <i class="fas fa-shipping-fast text-4xl text-blue-600 mb-4"></i>
            <h3 class="text-xl font-bold mb-2">Fast Shipping</h3>
            <p class="text-gray-600">Quick and reliable delivery to your doorstep</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <i class="fas fa-lock text-4xl text-blue-600 mb-4"></i>
            <h3 class="text-xl font-bold mb-2">Secure Payment</h3>
            <p class="text-gray-600">Your transactions are safe and secure</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <i class="fas fa-undo text-4xl text-blue-600 mb-4"></i>
            <h3 class="text-xl font-bold mb-2">Easy Returns</h3>
            <p class="text-gray-600">30-day hassle-free return policy</p>
        </div>
    </div>
</section>
@endsection
