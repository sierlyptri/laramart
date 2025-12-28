@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - Admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i> Back to Orders
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Order Details -->
    <div class="lg:col-span-2">
        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold mb-4">Order #{{ $order->id }}</h1>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Customer</p>
                    <p class="font-bold">{{ $order->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Order Date</p>
                    <p class="font-bold">{{ $order->created_at->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $order->created_at->format('H:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Total Amount</p>
                    <p class="font-bold text-2xl text-blue-600">${{ number_format($order->total_price, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="bg-gray-100 p-6 border-b">
                <h2 class="text-2xl font-bold">Order Items</h2>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left">Product</th>
                        <th class="px-6 py-4 text-left">Price</th>
                        <th class="px-6 py-4 text-left">Quantity</th>
                        <th class="px-6 py-4 text-left">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded mr-4">
                                    @endif
                                    <a href="{{ route('products.show', $item->product->slug) }}" target="_blank" class="hover:text-blue-600">
                                        {{ $item->product->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4">${{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 font-bold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Shipping Address -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Shipping Address</h2>
            <p class="text-gray-700 whitespace-pre-wrap bg-gray-50 p-4 rounded-lg">{{ $order->shipping_address }}</p>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Order Status Update -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-6">Update Status</h2>

            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="status" class="block font-bold mb-2">Order Status:</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        <option value="" disabled selected>Select status...</option>
                        <option value="pending" @if($order->status === 'pending') selected @endif>Pending</option>
                        <option value="processing" @if($order->status === 'processing') selected @endif>Processing</option>
                        <option value="shipped" @if($order->status === 'shipped') selected @endif>Shipped</option>
                        <option value="completed" @if($order->status === 'completed') selected @endif>Completed</option>
                        <option value="cancelled" @if($order->status === 'cancelled') selected @endif>Cancelled</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-bold">
                    Update Status
                </button>
            </form>
        </div>

        <!-- Status Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-6">Status Timeline</h2>

            <div class="space-y-4">
                @php
                    $statuses = ['pending', 'processing', 'shipped', 'completed'];
                    $currentStatusIndex = array_search($order->status, $statuses);
                @endphp

                @foreach($statuses as $index => $status)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($index <= $currentStatusIndex && $order->status !== 'cancelled')
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-500 text-white">
                                    <i class="fas fa-check"></i>
                                </div>
                            @else
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-300 text-white">
                                    {{ $index + 1 }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="font-bold capitalize">{{ $status }}</p>
                            @if($index === $currentStatusIndex)
                                <p class="text-green-600 text-sm">Current Status</p>
                            @endif
                        </div>
                    </div>

                    @if($index < count($statuses) - 1)
                        <div class="ml-4 mb-2 border-l-2 border-gray-300 h-4"></div>
                    @endif
                @endforeach

                @if($order->status === 'cancelled')
                    <div class="flex items-start mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <i class="fas fa-times-circle text-red-600 mt-1 mr-3"></i>
                        <p class="text-red-700">This order has been cancelled.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Order Summary</h2>

            <div class="space-y-3 pb-6 border-b">
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->total_price, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Shipping:</span>
                    <span class="text-green-600">Free</span>
                </div>
                <div class="flex justify-between">
                    <span>Tax (10%):</span>
                    <span>${{ number_format($order->total_price * 0.1, 2) }}</span>
                </div>
            </div>

            <div class="flex justify-between text-lg font-bold mt-6">
                <span>Total:</span>
                <span class="text-blue-600">${{ number_format($order->total_price * 1.1, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
