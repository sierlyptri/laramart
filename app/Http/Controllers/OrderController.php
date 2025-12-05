<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. Tampilkan Halaman Form Checkout (Isi Alamat)
    public function checkoutPage()
    {
        // Cek keranjang dulu
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        // Kalau kosong, usir ke home
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Keranjang kamu kosong nih!');
        }

        return view('checkout_page', [
            'cartItems' => $cartItems
        ]);
    }
    
    public function processCheckout(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|min:10',
        ]);
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang kamu kosong nih!');
        }
        try {
            DB::beginTransaction();
            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $totalPrice += ($item->product->price * $item->quantity);
            }
            $order = Order::create([
                'user_id' => Auth::id(),
                'code' => 'INV-' . time(),
                'total_price' => $totalPrice,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address, 
            ]);
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->product->price,
                ]);
                $product = Product::find($item->product_id);
                if($product->stock >= $item->quantity) {
                     $product->decrement('stock', $item->quantity);
                }
            }
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();
            return redirect()->route('order.show', $order->id)->with('success', 'Checkout Berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }

    // 3. Tampilkan Invoice
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);
        
        // Pastikan user cuma bisa liat order punya sendiri (Security)
        if ($order->user_id != Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        $orderItems = OrderItem::with('product')->where('order_id', $id)->get();

        return view('invoice', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }
}