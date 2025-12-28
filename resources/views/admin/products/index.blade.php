@extends('layouts.app')

@section('title', 'Manage Products - Admin')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-4xl font-bold">Manage Products</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-bold">
        <i class="fas fa-plus mr-2"></i> Add Product
    </a>
</div>

<!-- Search Bar -->
<div class="mb-8 bg-white rounded-lg shadow-md p-6">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-4">
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
            <a href="{{ route('admin.products.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
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
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left">Product</th>
                        <th class="px-6 py-4 text-left">Price</th>
                        <th class="px-6 py-4 text-left">Stock</th>
                        <th class="px-6 py-4 text-left">Reviews</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded mr-4">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold">{{ $product->name }}</p>
                                        <p class="text-gray-600 text-sm">{{ Str::limit($product->description, 40) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-blue-600">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold @if($product->stock > 10) bg-green-100 text-green-800 @elseif($product->stock > 0) bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $product->reviews()->count() }} reviews
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        {{ $products->links() }}
    </div>
@else
    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
        <i class="fas fa-box text-6xl text-gray-400 mb-6"></i>
        <p class="text-gray-600 text-lg mb-6">No products yet.</p>
        <a href="{{ route('admin.products.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 inline-block">
            Create First Product
        </a>
    </div>
@endif
@endsection
