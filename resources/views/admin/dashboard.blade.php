@extends('layouts.app')

@section('title', 'Admin Dashboard - Laramart')

@section('content')
<h1 class="text-4xl font-bold mb-8">Admin Dashboard</h1>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Orders</p>
                <p class="text-4xl font-bold">{{ $totalOrders }}</p>
            </div>
            <i class="fas fa-shopping-bag text-4xl text-blue-600 opacity-20"></i>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Pending Orders</p>
                <p class="text-4xl font-bold">{{ $pendingOrders }}</p>
            </div>
            <i class="fas fa-clock text-4xl text-yellow-600 opacity-20"></i>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Completed Orders</p>
                <p class="text-4xl font-bold">{{ $completedOrders }}</p>
            </div>
            <i class="fas fa-check-circle text-4xl text-green-600 opacity-20"></i>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Products</p>
                <p class="text-4xl font-bold">{{ $totalProducts }}</p>
            </div>
            <i class="fas fa-box text-4xl text-purple-600 opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gray-100 p-6 border-b">
            <h2 class="text-2xl font-bold flex items-center">
                <i class="fas fa-clock mr-3 text-blue-600"></i>
                Recent Orders
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold">Order ID</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Total</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 font-bold">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td class="px-6 py-4">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 font-bold">${{ number_format($order->total_price, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 text-center border-t">
            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 font-bold">
                View All Orders →
            </a>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gray-100 p-6 border-b">
            <h2 class="text-2xl font-bold flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-red-600"></i>
                Low Stock Products
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold">Product</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Stock</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ Str::limit($product->name, 25) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800">
                                    {{ $product->stock }} units
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 text-center border-t">
            <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800 font-bold">
                Manage Products →
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <a href="{{ route('admin.products.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
        <i class="fas fa-plus-circle text-4xl mb-4"></i>
        <h3 class="text-xl font-bold">Add New Product</h3>
        <p class="text-blue-100 mt-2">Create a new product</p>
    </a>

    <a href="{{ route('admin.orders.index') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
        <h3 class="text-xl font-bold">Manage Orders</h3>
        <p class="text-purple-100 mt-2">View and update orders</p>
    </a>

    <a href="{{ route('admin.products.index') }}" class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow text-center">
        <i class="fas fa-boxes text-4xl mb-4"></i>
        <h3 class="text-xl font-bold">Manage Products</h3>
        <p class="text-green-100 mt-2">Edit and manage products</p>
    </a>
</div>
@endsection
