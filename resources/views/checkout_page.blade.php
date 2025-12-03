<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Laravel Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/style.css'])
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <span style="font-size: 1.5rem;">🛒</span> Laravel Mart
            </a>
            <span class="text-white fw-bold">Secure Checkout 🔒</span>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        
        <a href="{{ route('cart.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block">
            <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
        </a>

        <h2 class="fw-bold mb-4">Pengiriman & Pembayaran</h2>

        <div class="row g-4">
            
            <div class="col-md-7">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Alamat Pengiriman</h5>
                        
                        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label text-muted">Penerima</label>
                                <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }} ({{ Auth::user()->email }})" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" class="form-control" rows="4" 
                                          placeholder="Contoh: Jl. Sudirman No. 45, RT 01/RW 02, Jakarta Selatan, 12190..." required></textarea>
                                <div class="form-text">Pastikan alamat lengkap agar kurir tidak nyasar.</div>
                                @error('shipping_address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <h5 class="fw-bold mb-3"><i class="bi bi-credit-card-2-front-fill text-primary me-2"></i>Metode Pembayaran</h5>
                            
                            <div class="d-flex gap-3 mb-3">
                                <div class="border rounded p-3 w-100 text-center border-primary bg-primary-subtle text-primary fw-bold">
                                    <i class="bi bi-wallet2"></i> Transfer Bank / QRIS
                                </div>
                                <div class="border rounded p-3 w-100 text-center text-muted opacity-50">
                                    <i class="bi bi-cash"></i> COD (Tidak Tersedia)
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>

                        @php $total = 0; @endphp
                        <div class="d-flex flex-column gap-3 mb-4">
                            @foreach($cartItems as $item)
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('images/' . $item->product->image) }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                                    <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</small>
                                </div>
                                <div class="fw-bold">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                </div>
                            </div>
                            @php $total += ($item->product->price * $item->quantity); @endphp
                            @endforeach
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal Produk</span>
                            <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span class="text-success fw-bold">Gratis</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
                            <h5 class="mb-0 fw-bold">Total Tagihan</h5>
                            <h4 class="mb-0 fw-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                        </div>

                        <button type="submit" form="checkoutForm" class="btn btn-success w-100 py-3 fw-bold shadow">
                            <i class="bi bi-shield-lock-fill me-2"></i> Bayar Sekarang
                        </button>
                        <p class="text-center text-muted mt-3" style="font-size: 0.8rem;">
                            <i class="bi bi-shield-check"></i> Transaksi Anda dilindungi enkripsi SSL.
                        </p>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>