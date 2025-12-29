<?php
@extends('layouts.app')

@section('title', 'Add Product - Admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i> Back to Products
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6">Add New Product</h1>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="name" class="block font-bold mb-2">Product Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('name') border-red-500 @enderror" required>
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block font-bold mb-2">Description:</label>
                <textarea id="description" name="description" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="price" class="block font-bold mb-2">Price ($):</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('price') border-red-500 @enderror" required>
                    @error('price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="stock" class="block font-bold mb-2">Stock:</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('stock') border-red-500 @enderror" required>
                    @error('stock') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category_id" class="block font-bold mb-2">Category:</label>
                <select name="category_id" id="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('category_id') border-red-500 @enderror">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if(old('category_id') == $cat->id) selected @endif>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="image" class="block font-bold mb-2">Product Image:</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-gray-400">
                    <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                    <label for="image" class="cursor-pointer block">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600">Click to upload or drag and drop</p>
                        <p class="text-gray-500 text-sm">PNG, JPG, GIF up to 2MB (min 600x600)</p>
                    </label>
                </div>
                <div id="preview" class="mt-4 hidden">
                    <img id="previewImg" src="" alt="Preview" class="max-w-xs rounded-lg">
                </div>
                @error('image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-bold">
                    <i class="fas fa-save mr-2"></i> Add Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="flex-1 bg-gray-400 text-white px-6 py-3 rounded-lg hover:bg-gray-500 font-bold text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('preview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection
