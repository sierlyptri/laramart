@extends('layouts.app')

@section('title', 'Shopping Cart - Laramart')

@section('content')
<h1 class="text-4xl font-bold mb-8">Shopping Cart</h1>

@if(count($cart) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left">Product</th>
                            <th class="px-6 py-4 text-left">Price</th>
                            <th class="px-6 py-4 text-left">Quantity</th>
                            <th class="px-6 py-4 text-left">Subtotal</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($item['product']->image)
                                            <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-12 h-12 object-cover rounded mr-4">
                                        @endif
                                        <a href="{{ route('products.show', $item['product']->slug) }}" class="hover:text-blue-600">
                                            {{ $item['product']->name }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4">${{ number_format($item['product']->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" class="w-16 px-2 py-1 border border-gray-300 rounded">
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                            Update
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 font-bold">${{ number_format($item['subtotal'], 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" onsubmit="return confirm('Remove from cart?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-lg p-6 h-fit">
            <h2 class="text-2xl font-bold mb-6">Order Summary</h2>

            <div class="mb-6 space-y-3 pb-6 border-b">
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Shipping:</span>
                    <span class="text-green-600">Free</span>
                </div>
                <div class="flex justify-between">
                    <span>Tax (estimated):</span>
                    <span>${{ number_format($total * 0.1, 2) }}</span>
                </div>
            </div>

            <div class="flex justify-between text-xl font-bold mb-6">
                <span>Total:</span>
                <span>${{ number_format($total * 1.1, 2) }}</span>
            </div>

            <!-- Checkout Form -->
            <form action="{{ route('customer.checkout') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="shipping_address" class="block font-bold mb-2">Shipping Address:</label>
                    <textarea id="shipping_address" name="shipping_address" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('shipping_address') border-red-500 @enderror" required placeholder="Enter your shipping address...">{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 text-lg">
                    <i class="fas fa-credit-card mr-2"></i> Checkout
                </button>
            </form>

            <a href="{{ route('products.index') }}" class="block text-center mt-4 text-blue-600 hover:text-blue-800">
                Continue Shopping
            </a>

            <form action="{{ route('cart.clear') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="w-full text-red-600 hover:text-red-800 py-2 border border-red-600 rounded-lg">
                    Clear Cart
                </button>
            </form>
        </div>
    </div>
@else
    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
        <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-6"></i>
        <h2 class="text-2xl font-bold mb-4">Your cart is empty</h2>
        <p class="text-gray-600 mb-6">Add some products to get started!</p>
        <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Start Shopping
        </a>
    </div>
@endif
@endsection
