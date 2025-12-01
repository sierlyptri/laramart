<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        return view('home', [
            'products' => $products
        ]);
    }

    public function show($slug)
    {
        // Cari produk berdasarkan slug-nya
        // Kalau gak ketemu, otomatis 404 Not Found
        $product = Product::where('slug', $slug)->firstOrFail();

        return view('details', [
            'product' => $product
        ]);
    }
}
