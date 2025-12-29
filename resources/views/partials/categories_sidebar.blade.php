<?php
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <h4 class="font-bold mb-3">Categories</h4>
    <ul class="space-y-2 text-sm">
        <li>
            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600">All</a>
        </li>
        @foreach($categories as $cat)
            <li>
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="text-gray-700 hover:text-blue-600 {{ (isset($categoryParam) && $categoryParam == $cat->slug) || (isset($categoryId) && $categoryId == $cat->id) ? 'font-bold text-blue-600' : '' }}">
                    {{ $cat->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>