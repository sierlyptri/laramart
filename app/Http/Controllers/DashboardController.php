<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalProducts = Product::count();
        $recentOrders = Order::with('user')->latest()->limit(5)->get();
        $lowStockProducts = Product::where('stock', '<', 10)->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalProducts',
            'recentOrders',
            'lowStockProducts'
        ));
    }

    /**
     * User Dashboard (Home)
     */
    public function home()
    {
        $featuredProducts = Product::inRandomOrder()->limit(8)->get();
        return view('home', compact('featuredProducts'));
    }
}
