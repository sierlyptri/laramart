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

    // 2. Proses Checkout (Simpan ke Database)
    public function processCheckout(Request $request)
    {
        // Validasi: Alamat wajib diisi minimal 10 karakter
        $request->validate([
            'shipping_address' => 'required|string|min:10',
        ]);

        // Ambil keranjang user
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang kamu kosong nih!');
        }

        // START TRANSACTION
        try {
            DB::beginTransaction();

            // Hitung Total Harga
            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $totalPrice += ($item->product->price * $item->quantity);
            }

            // Buat Data Order Utama
            $order = Order::create([
                'user_id' => Auth::id(),
                'code' => 'INV-' . time(),
                'total_price' => $totalPrice,
                'status' => 'pending',
                // 👇 AMBIL ALAMAT DARI FORM INPUT USER
                'shipping_address' => $request->shipping_address, 
            ]);

            // Pindahkan isi Keranjang ke Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->product->price,
                ]);

                // Kurangi Stok Produk
                $product = Product::find($item->product_id);
                // Cek stok cukup gak (optional safety)
                if($product->stock >= $item->quantity) {
                     $product->decrement('stock', $item->quantity);
                }
            }

            // Kosongkan Keranjang
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // Lempar ke Halaman Invoice
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