<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

  
    public function adminIndex()
    {
        $orders = Order::with('user')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function adminShow(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Admin: Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Order status updated!');
    }

    /**
     * Checkout: Create order from cart
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string|min:10',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        // Calculate total price
        $totalPrice = 0;
        foreach ($cart as $item) {
            $product = Product::findOrFail($item['product_id']);
            $totalPrice += $product->price * $item['quantity'];
        }

        // Create order
        $order = auth()->user()->orders()->create([
            'total_price' => $totalPrice,
            'shipping_address' => $validated['shipping_address'],
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($cart as $item) {
            $product = Product::findOrFail($item['product_id']);
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);

            // Reduce stock
            $product->decrement('stock', $item['quantity']);
        }

        // Clear cart
        session()->forget('cart');

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully! We will process it soon.');
    }
}
