<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Laravel Mart</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Vite CSS & JS -->
    <!-- Ini akan memanggil style.css dan app.js yang sudah kita edit -->
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container">
            <!-- Asumsi route home bernama 'home' -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <span>⬅</span>
                <span>Kembali ke Home</span>
            </a>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row g-4">
            <!-- Kolom Gambar -->
            <div class="col-lg-5 fade-in">
                <div class="product-image-wrapper">
                    <img src="{{ asset('images/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                </div>
            </div>

            <!-- Kolom Detail -->
            <div class="col-lg-7 fade-in">
                <div class="product-details">
                    <span class="category-badge">📦 {{ $product->category->name }}</span>
                    
                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    <div class="price-wrapper">
                        <span class="product-price">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="stock-badge">✓ Stok Tersedia</span>
                    </div>

                    <div class="product-description">
                        <strong style="color: var(--dark); display: block; margin-bottom: 0.5rem;">Deskripsi Produk</strong>
                        {{ $product->description }}
                    </div>

                    <hr class="divider">

                    <!-- Quantity Section -->
                    <div class="quantity-control">
                        <span class="quantity-label">Jumlah:</span>
                        <div class="quantity-buttons">
                            <button class="qty-btn" onclick="decreaseQty()">−</button>
                            <input type="number" id="quantity" value="1" min="1" max="99" class="qty-input" readonly>
                            <button class="qty-btn" onclick="increaseQty()">+</button>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-add-cart">
                            <span style="font-size: 1.3rem;">🛒</span>
                            <span>Masukkan Keranjang</span>
                        </button>
                        <button class="btn-buy-now">
                            Beli Sekarang
                        </button>
                    </div>

                    <!-- Info Icons -->
                    <div class="info-section">
                        <div class="info-item">
                            <span class="info-icon">🚚</span>
                            <div>
                                <strong>Pengiriman Cepat</strong>
                                <p class="mb-0 text-muted small">Estimasi 2-3 hari kerja</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-icon">🔒</span>
                            <div>
                                <strong>Pembayaran Aman</strong>
                                <p class="mb-0 text-muted small">Berbagai metode pembayaran tersedia</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-icon">↩️</span>
                            <div>
                                <strong>Garansi 7 Hari</strong>
                                <p class="mb-0 text-muted small">Pengembalian mudah jika ada masalah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>