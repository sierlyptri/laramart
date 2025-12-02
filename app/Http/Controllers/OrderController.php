<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Wajib import DB buat Transaction

class OrderController extends Controller
{
    public function checkout()
    {
        // 1. Ambil keranjang user
        $cartItems = Cart::where('user_id', Auth::id())->get();

        // Kalau keranjang kosong, tendang balik
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang kamu kosong nih!');
        }

        // START TRANSACTION (Mulai proses aman)
        try {
            DB::beginTransaction();

            // 2. Hitung Total Harga
            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $totalPrice += ($item->product->price * $item->quantity);
            }

            // 3. Buat Data Order Utama (Header)
            $order = Order::create([
                'user_id' => Auth::id(),
                'code' => 'INV-' . time(), // Bikin nomor invoice unik pake waktu
                'total_price' => $totalPrice,
                'status' => 'pending',
                'shipping_address' => 'Alamat default user (Nanti bisa dibikin form)',
            ]);

            // 4. Pindahkan isi Keranjang ke Order Items (Detail)
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->product->price, // Penting! Harga saat beli
                ]);

                // 5. Kurangi Stok Produk (Opsional tapi keren)
                $product = Product::find($item->product_id);
                $product->decrement('stock', $item->quantity);
            }

            // 6. Kosongkan Keranjang
            Cart::where('user_id', Auth::id())->delete();

            // Kalau semua lancar, RESMIKAN datanya
            DB::commit();

            return redirect()->route('home')->with('success', 'Checkout Berhasil! Nomor Invoice: ' . $order->code);

        } catch (\Exception $e) {
            // Kalau ada error, BATALKAN semua perubahan database
            DB::rollBack();
            return back()->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }
}