<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Mart - Modern E-Commerce</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/style.css', 'resources/js/app.js'])

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-cart3 me-2"></i> 
                <strong>LaraMart</strong>
            </a>
            
            <!-- Right Side Nav Items -->
            <div class="d-flex align-items-center ms-auto">
                <!-- Cart Icon with Badge -->
                <a href="#" class="position-relative me-4">
                    <i class="bi bi-cart3 fs-4 text-primary"></i>
                    <span class="cart-badge">3</span>
                </a>

                <!-- Search Bar (Moved to Right) -->
                <div class="search-container me-4">
                    <form action="{{ route('home') }}" method="GET" class="d-flex w-100">
                        <input class="form-control search-pill me-2" type="search" placeholder="Search products..." 
                               aria-label="Search" name="search">
                        <button class="btn btn-search d-none d-md-block" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Authentication Logic -->
                @auth
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                           id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Fallback avatar using UI Avatars if no user avatar -->
                            @php
                                $avatarUrl = auth()->user()->avatar 
                                    ? asset('storage/' . auth()->user()->avatar) 
                                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=2563eb&color=fff&size=36';
                            @endphp
                            <img src="{{ $avatarUrl }}" 
                                 alt="User Avatar" class="user-avatar me-2">
                            <span class="user-name d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="bi bi-bag me-2"></i>My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Login Button for Guests -->
                    <div class="d-flex gap-2 align-items-center">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-person-plus me-1"></i> Sign Up
                            </a>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container py-4">
        <!-- Hero Section -->
        <section class="hero-section text-center mb-5 fade-in">
            <h1 class="floating-animation mb-3">Temukan Produk Favoritmu</h1>
            <p class="lead text-muted mb-4">Belanja mudah, cepat, dan terpercaya</p>
            
            <!-- Bootstrap Carousel -->
            <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" 
                            aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" 
                            aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" 
                            aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/hero-1.jpg') }}" class="d-block w-100 carousel-img" alt="Summer Sale">
                        <div class="carousel-caption d-none d-md-block">
                            <h3>Summer Sale - Up to 50% Off</h3>
                            <p>Discover amazing deals on our top products. Limited time offer!</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/hero-2.jpg') }}" class="d-block w-100 carousel-img" alt="New Arrivals">
                        <div class="carousel-caption d-none d-md-block">
                            <h3>New Arrivals</h3>
                            <p>Check out our latest collection of premium products</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/hero-3.jpg') }}" class="d-block w-100 carousel-img" alt="Free Shipping">
                        <div class="carousel-caption d-none d-md-block">
                            <h3>Free Shipping on Orders Over $50</h3>
                            <p>Enjoy free delivery on all orders above $50. Shop now!</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" 
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" 
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="product-section">
            <h2 class="section-title">Produk Terbaru</h2>
            
            <!-- Check if products exist -->
            @if(isset($products) && $products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 fade-in">
                        <div class="product-card shadow-sm">
                            <div class="product-image-wrapper">
                                @if(isset($item->category) && isset($item->category->name))
                                <span class="category-badge">{{ $item->category->name }}</span>
                                @endif
                                @if(isset($item->image))
                                <img src="{{ asset('images/' . $item->image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $item->name ?? 'Product Image' }}">
                                @else
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     class="card-img-top" 
                                     alt="Default Product Image">
                                @endif
                            </div>
                            
                            <div class="card-body">
                                <h5 class="product-title">{{ $item->name ?? 'Product Name' }}</h5>
                                <p class="product-price">
                                    Rp {{ isset($item->price) ? number_format($item->price, 0, ',', '.') : '0' }}
                                </p>
                                <a href="#" class="btn btn-detail">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- pagination --}}
                @if(method_exists($products, 'links'))
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
            <!-- Fallback: Show sample products if no products in database -->
            <div class="row g-4">
                <div class="col-lg-3 col-md-4 col-sm-6 fade-in">
                    <div class="product-card shadow-sm">
                        <div class="product-image-wrapper">
                            <span class="category-badge">Electronics</span>
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 class="card-img-top" 
                                 alt="Wireless Headphones">
                        </div>
                        <div class="card-body">
                            <h5 class="product-title">Premium Wireless Bluetooth Headphones</h5>
                            <p class="product-price">Rp 1.299.000</p>
                            <a href="#" class="btn btn-detail">Lihat Detail →</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-4 col-sm-6 fade-in">
                    <div class="product-card shadow-sm">
                        <div class="product-image-wrapper">
                            <span class="category-badge">Electronics</span>
                            <img src="https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 class="card-img-top" 
                                 alt="Camera">
                        </div>
                        <div class="card-body">
                            <h5 class="product-title">Professional DSLR Camera</h5>
                            <p class="product-price">Rp 8.999.000</p>
                            <a href="#" class="btn btn-detail">Lihat Detail →</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-4 col-sm-6 fade-in">
                    <div class="product-card shadow-sm">
                        <div class="product-image-wrapper">
                            <span class="category-badge">Wearables</span>
                            <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 class="card-img-top" 
                                 alt="Smart Watch">
                        </div>
                        <div class="card-body">
                            <h5 class="product-title">Smart Watch with Fitness Tracking</h5>
                            <p class="product-price">Rp 2.499.000</p>
                            <a href="#" class="btn btn-detail">Lihat Detail →</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-4 col-sm-6 fade-in">
                    <div class="product-card shadow-sm">
                        <div class="product-image-wrapper">
                            <span class="category-badge">Fashion</span>
                            <img src="https://images.unsplash.com/photo-1600003263720-95b45a4035d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 class="card-img-top" 
                                 alt="Running Shoes">
                        </div>
                        <div class="card-body">
                            <h5 class="product-title">Premium Running Shoes</h5>
                            <p class="product-price">Rp 899.000</p>
                            <a href="#" class="btn btn-detail">Lihat Detail →</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="footer-heading">Laravel Mart</h5>
                    <p class="text-muted">Your trusted online marketplace for quality products at affordable prices.</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="me-3 text-secondary"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="me-3 text-secondary"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="me-3 text-secondary"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-linkedin fs-5"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-heading">Shop</h5>
                    <a href="#" class="footer-link">All Products</a>
                    <a href="#" class="footer-link">Categories</a>
                    <a href="#" class="footer-link">Featured</a>
                    <a href="#" class="footer-link">On Sale</a>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Support</h5>
                    <a href="#" class="footer-link">FAQ</a>
                    <a href="#" class="footer-link">Contact Us</a>
                    <a href="#" class="footer-link">Shipping Policy</a>
                    <a href="#" class="footer-link">Returns & Refunds</a>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-heading">Newsletter</h5>
                    <p class="text-muted mb-3">Subscribe to get updates on new arrivals and special offers.</p>
                    <form action="#" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Your email" name="email">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="copyright">
                © {{ date('Y') }} Laravel Mart. Sierly Putri Anjani
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Auto-rotate carousel
            var carouselElement = document.getElementById('heroCarousel');
            if (carouselElement) {
                var carousel = new bootstrap.Carousel(carouselElement, {
                    interval: 4000,
                    wrap: true
                });
            }
        });
    </script>
</body>
</html>