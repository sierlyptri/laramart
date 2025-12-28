@extends('layouts.app')

@section('title', 'My Orders - Laramart')

@section('content')
<h1 class="text-4xl font-bold mb-8">My Orders</h1>

@if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                    <!-- Order ID -->
                    <div>
                        <p class="text-gray-600 text-sm">Order ID</p>
                        <p class="font-bold text-lg">#{{ $order->id }}</p>
                    </div>

                    <!-- Date -->
                    <div>
                        <p class="text-gray-600 text-sm">Date</p>
                        <p class="font-bold">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>

                    <!-- Total -->
                    <div>
                        <p class="text-gray-600 text-sm">Total</p>
                        <p class="font-bold text-lg text-blue-600">${{ number_format($order->total_price, 2) }}</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <p class="text-gray-600 text-sm">Status</p>
                        <span class="px-3 py-1 rounded-full text-sm font-bold 
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <!-- Action -->
                    <div class="text-right">
                        <a href="{{ route('customer.orders.show', $order) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 inline-block">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        {{ $orders->links() }}
    </div>
@else
    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
        <i class="fas fa-inbox text-6xl text-gray-400 mb-6"></i>
        <h2 class="text-2xl font-bold mb-4">No orders yet</h2>
        <p class="text-gray-600 mb-6">Start shopping to create your first order!</p>
        <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Shop Now
        </a>
    </div>
@endif
@endsection
