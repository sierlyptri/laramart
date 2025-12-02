<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Mart - Modern E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @vite(['resources/css/style.css'])
</head>
<body>
    <nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">🛒 Laravel Mart</a>

        <div>
            @auth
                <span class="text-white me-3">Halo, {{ Auth::user()->name }} 👋</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-danger btn-sm">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-sm text-primary fw-bold">Login</a>
            @endauth
        </div>
    </div>
    </nav>

    <div class="container mt-4">
        <div class="hero-section text-center">
            <h1 class="floating-animation">Temukan Produk Favoritmu</h1>
            <p class="lead text-muted">Belanja mudah, cepat, dan terpercaya</p>
            
            <div class="search-bar">
                <input type="text" class="form-control" placeholder="🔍 Cari produk...">
            </div>
        </div>

        <h2 class="section-title">Produk Terbaru</h2>
        
        <div class="row g-4">
            @foreach($products as $item)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card shadow-sm">
                    <div class="img-wrapper">
                        <span class="category-badge">{{ $item->category->name }}</span>
                        <img src="{{ asset('images/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                    </div>
                    
                    <div class="card-body">
                        <h5 class="product-title">{{ $item->name }}</h5>
                        <p class="product-price">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </p>
                        <a href="{{ route('product.show', $item->slug) }}" class="btn btn-detail">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="container mt-5 mb-5 text-center">
        <p class="text-muted">© 2024 Laravel Mart. Sierly Putri Anjani</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>