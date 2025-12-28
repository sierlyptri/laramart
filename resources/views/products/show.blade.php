@extends('layouts.app')

@section('title', $product->name . ' - Laramart')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
    <!-- Product Image -->
    <div>
        <div class="bg-gray-200 rounded-lg overflow-hidden mb-4" style="height: 500px;">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-6xl"></i>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Info -->
    <div>
        <h1 class="text-4xl font-bold mb-4">{{ $product->name }}</h1>

        <!-- Rating -->
        <div class="flex items-center mb-4">
            <div class="text-yellow-400 text-lg">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($averageRating ?? 0))
                        <i class="fas fa-star"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
            </div>
            <span class="text-gray-600 ml-4">({{ $product->reviews()->count() }} reviews)</span>
        </div>

        <!-- Price -->
        <div class="mb-6">
            <span class="text-5xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <h2 class="text-xl font-bold mb-2">Description</h2>
            <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
        </div>

        <!-- Stock Status -->
        <div class="mb-6">
            <span class="text-lg font-bold @if($product->stock > 0) text-green-600 @else text-red-600 @endif">
                @if($product->stock > 0)
                    ✓ In Stock ({{ $product->stock }} available)
                @else
                    ✗ Out of Stock
                @endif
            </span>
        </div>

        <!-- Add to Cart -->
        @auth
            @if(auth()->user()->role === 'user')
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex items-center gap-4 mb-4">
                            <label for="quantity" class="font-bold">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 text-lg">
                            <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                        </button>
                    </form>
                @endif
            @endif
        @else
            <a href="{{ route('login') }}" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 text-lg text-center block">
                Login to Add to Cart
            </a>
        @endauth

        <!-- Share Buttons -->
        <div class="mt-8 pt-8 border-t">
            <p class="font-bold mb-4">Share this product:</p>
            <div class="flex gap-4">
                <a href="#" class="text-gray-600 hover:text-blue-600"><i class="fab fa-facebook text-2xl"></i></a>
                <a href="#" class="text-gray-600 hover:text-blue-400"><i class="fab fa-twitter text-2xl"></i></a>
                <a href="#" class="text-gray-600 hover:text-pink-600"><i class="fab fa-pinterest text-2xl"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Reviews Section -->
<div class="bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-3xl font-bold mb-8">Customer Reviews</h2>

    @auth
        @if(auth()->user()->role === 'user')
            <!-- Review Form -->
            @php
                $hasCompletedOrder = \App\Models\Order::where('user_id', auth()->id())
                    ->where('status', 'completed')
                    ->whereHas('items', function ($q) use ($product) {
                        $q->where('product_id', $product->id);
                    })
                    ->exists();
            @endphp

            @if($hasCompletedOrder)
                <form action="{{ route('customer.reviews.store', $product) }}" method="POST" class="mb-8 p-6 bg-blue-50 rounded-lg">
                    @csrf
                    <h3 class="text-xl font-bold mb-4">Write a Review</h3>

                    <div class="mb-4">
                        <label for="rating" class="block font-bold mb-2">Rating:</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden peer" required>
                                <label for="star{{ $i }}" class="text-3xl text-gray-400 cursor-pointer peer-checked:text-yellow-400 hover:text-yellow-400">
                                    ★
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="block font-bold mb-2">Comment:</label>
                        <textarea id="comment" name="comment" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Share your experience..."></textarea>
                        @error('comment')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-bold">
                        Submit Review
                    </button>
                </form>
            @else
                <div class="mb-8 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-yellow-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        You can only review products from completed orders.
                    </p>
                </div>
            @endif
        @endif
    @endauth

    <!-- Reviews List -->
    @if($reviews->count() > 0)
        <div class="space-y-6">
            @foreach($reviews as $review)
                <div class="border-b pb-6 last:border-b-0">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-bold text-lg">{{ $review->user->name }}</h4>
                            <p class="text-gray-600 text-sm">{{ $review->created_at->format('M d, Y') }}</p>
                        </div>
                        @if(auth()->check() && (auth()->id() === $review->user_id || auth()->user()->role === 'admin'))
                            <form action="{{ route('customer.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Rating Stars -->
                    <div class="text-yellow-400 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span class="text-gray-600 text-sm ml-2">({{ $review->rating }}/5)</span>
                    </div>

                    <!-- Review Comment -->
                    @if($review->comment)
                        <p class="text-gray-700">{{ $review->comment }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    @else
        <p class="text-gray-600 text-center py-8">No reviews yet. Be the first to review this product!</p>
    @endif
</div>
@endsection
