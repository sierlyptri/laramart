@extends('layouts.app')

@section('title', 'Manage Orders - Admin')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold">Manage Orders</h1>
</div>

@if($orders->count() > 0)
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left">Order ID</th>
                        <th class="px-6 py-4 text-left">Customer</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-left">Items</th>
                        <th class="px-6 py-4 text-left">Total</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold">#{{ $order->id }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-bold">{{ $order->user->name }}</p>
                                    <p class="text-gray-600 text-sm">{{ $order->user->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">{{ $order->items->count() }} items</td>
                            <td class="px-6 py-4 font-bold text-blue-600">${{ number_format($order->total_price, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold 
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 font-bold">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        {{ $orders->links() }}
    </div>
@else
    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
        <i class="fas fa-inbox text-6xl text-gray-400 mb-6"></i>
        <p class="text-gray-600 text-lg">No orders found.</p>
    </div>
@endif
@endsection