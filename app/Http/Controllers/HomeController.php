<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(Request $request) 
    {
        // jika ada filter pencarian
        $search = $request->query('search');

        $products = Product::query()
            ->when($search, fn($q, $s) => $q->where('name', 'like', '%' . $s . '%'))
            ->orderBy('created_at', 'desc')
            // ->get(); // gunakan ini jika ingin semua
            ->paginate(12); // atau paginate agar tidak memuat semua sekaligus

        return view('home', compact('products'));
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
