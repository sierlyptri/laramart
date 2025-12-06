<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Mart - Modern E-Commerce Platform</title>
    <meta name="description" content="Shop the latest products at amazing prices. Free shipping on orders over $50.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Styles -->
    @vite(['resources/css/style.css'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="navbar-brand">
                <div class="navbar-brand-icon">
                    <i class="bi bi-cart3"></i>
                </div>
                <span>LaraMart</span>
            </a>

            <!-- Search -->
            <div class="navbar-search">
                <form class="search-form" action="{{ route('home') }}" method="GET">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" 
                           class="search-input" 
                           placeholder="Search products, brands, and categories..." 
                           name="q"
                           value="{{ request('q') }}">
                </form>
            </div>

            <!-- Actions -->
            <div class="navbar-actions">
                <a href="{{ route('home') }}" class="navbar-link active">
                    <i class="bi bi-house"></i>
                    <span>Home</span>
                </a>
                
                <a href="{{ route('products.index') }}" class="navbar-link">
                    <i class="bi bi-shop"></i>
                    <span>Shop</span>
                </a>
                
                <a href="{{ route('cart.index') }}" class="navbar-link cart-link">
                    <i class="bi bi-cart3"></i>
                    <span>Cart</span>
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                <!-- User Menu -->
                @auth
                    <div class="user-dropdown">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff' }}" 
                             alt="{{ Auth::user()->name }}"
                             class="user-avatar">
                        
                        <div class="dropdown-menu">
                            <div class="dropdown-header">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff' }}" 
                                         alt="{{ Auth::user()->name }}"
                                         class="user-avatar" style="width: 2rem; height: 2rem;">
                                    <div>
                                        <strong>{{ Auth::user()->name }}</strong>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="dropdown-divider"></div>
                            
                            <a href="{{ route('profile.show') }}" class="dropdown-item">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                            
                            <a href="{{ route('orders.index') }}" class="dropdown-item">
                                <i class="bi bi-bag"></i>
                                <span>My Orders</span>
                            </a>
                            
                            <a href="{{ route('wishlist.index') }}" class="dropdown-item">
                                <i class="bi bi-heart"></i>
                                <span>Wishlist</span>
                            </a>
                            
                            <div class="dropdown-divider"></div>
                            
                            <form method="POST" action="{{ route('logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-person-plus"></i>
                            <span>Sign Up</span>
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-container">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Discover Amazing Products at Unbeatable Prices
                    </h1>
                    <p class="hero-subtitle">
                        Shop from thousands of products across multiple categories. 
                        Free shipping on orders over $50. 30-day return policy.
                    </p>
                    
                    <div class="d-flex gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-right"></i>
                            <span>Shop Now</span>
                        </a>
                        <a href="#categories" class="btn btn-outline btn-lg">
                            <i class="bi bi-grid-3x3-gap"></i>
                            <span>Browse Categories</span>
                        </a>
                    </div>
                    
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">10K+</span>
                            <span class="stat-label">Products</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">4.8</span>
                            <span class="stat-label">Customer Rating</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Support</span>
                        </div>
                    </div>
                </div>
                
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" 
                         alt="Shopping Experience">
                    <div class="floating-badge">
                        <div class="text-center">
                            <div class="fw-bold">Up to 50% OFF</div>
                            <small>Summer Sale</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">
                    Browse products from our popular categories
                </p>
            </div>
            
            <div class="products-grid">
                @foreach($categories as $category)
                <a href="{{ route('products.category', $category->slug) }}" class="product-card">
                    <div class="product-image">
                        <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
                             alt="{{ $category->name }}">
                    </div>
                    <div class="product-content">
                        <h3 class="product-title">{{ $category->name }}</h3>
                        <p class="product-description">{{ $category->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ $category->products_count }} products</span>
                            <i class="bi bi-arrow-right text-primary"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="section bg-neutral-50">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">
                    Discover our handpicked selection of premium products
                </p>
            </div>
            
            <div class="products-filter">
                <button class="filter-btn active" data-filter="all">All</button>
                @foreach($categories->take(5) as $category)
                <button class="filter-btn" data-filter="{{ $category->slug }}">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
            
            <div class="products-grid">
                @foreach($featuredProducts as $product)
                <div class="product-card" data-category="{{ $product->category->slug }}">
                    <div class="product-badges">
                        @if($product->is_new)
                        <span class="badge badge-new">New</span>
                        @endif
                        @if($product->discount > 0)
                        <span class="badge badge-sale">Sale</span>
                        @endif
                        <span class="badge badge-category">{{ $product->category->name }}</span>
                    </div>
                    
                    <div class="product-image">
                        <img src="{{ asset('storage/' . $product->images->first()->path ?? 'default-product.jpg') }}" 
                             alt="{{ $product->name }}">
                        <button class="product-wishlist" data-product-id="{{ $product->id }}">
                            <i class="bi bi-heart{{ Auth::check() && $product->isWishlistedBy(Auth::user()) ? '-fill' : '' }}"></i>
                        </button>
                    </div>
                    
                    <div class="product-content">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                        
                        <div class="product-rating">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->average_rating))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($i - 0.5 <= $product->average_rating)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating-count">({{ $product->reviews_count }})</span>
                        </div>
                        
                        <div class="product-price">
                            @if($product->discount > 0)
                                <span class="price-current">
                                    ${{ number_format($product->price * (1 - $product->discount/100), 2) }}
                                </span>
                                <span class="price-original">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            @else
                                <span class="price-current">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="product-actions">
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-add-to-cart">
                                    <i class="bi bi-cart-plus"></i>
                                    <span>Add to Cart</span>
                                </button>
                            </form>
                            <a href="{{ route('products.show', $product->slug) }}" 
                               class="btn btn-outline btn-quick-view">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <span>View All Products</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Promo Banner -->
    <section class="section">
        <div class="container">
            <div class="bg-gradient-to-r from-primary-600 to-secondary-600 rounded-2xl p-8 text-white">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="text-3xl font-bold mb-3">Summer Sale is Live! 🎉</h3>
                        <p class="text-lg opacity-90 mb-4">
                            Get up to 50% off on selected items. Limited time offer.
                        </p>
                        <div class="d-flex align-items-center gap-4">
                            <div class="text-center">
                                <div class="text-4xl font-bold">24</div>
                                <div class="text-sm opacity-75">Hours</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold">59</div>
                                <div class="text-sm opacity-75">Minutes</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold">59</div>
                                <div class="text-sm opacity-75">Seconds</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('products.sale') }}" class="btn btn-light btn-lg">
                            <span>Shop Sale</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">What Our Customers Say</h2>
                <p class="section-subtitle">
                    Join thousands of satisfied customers
                </p>
            </div>
            
            <div class="row g-4">
                @foreach($testimonials as $testimonial)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <img src="{{ $testimonial->avatar }}" 
                                     alt="{{ $testimonial->name }}"
                                     class="rounded-circle" 
                                     style="width: 48px; height: 48px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0">{{ $testimonial->name }}</h6>
                                    <small class="text-muted">{{ $testimonial->role }}</small>
                                </div>
                            </div>
                            <div class="stars mb-3">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @endfor
                            </div>
                            <p class="mb-0">"{{ $testimonial->comment }}"</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="section bg-neutral-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-3">Stay Updated</h2>
                    <p class="text-muted mb-4">
                        Subscribe to our newsletter for the latest products and exclusive offers
                    </p>
                    
                    <form class="newsletter-form justify-content-center" method="POST" action="{{ route('newsletter.subscribe') }}">
                        @csrf
                        <input type="email" 
                               class="form-control w-auto" 
                               placeholder="Your email address"
                               name="email"
                               required>
                        <button type="submit" class="btn btn-primary">
                            <span>Subscribe</span>
                            <i class="bi bi-envelope"></i>
                        </button>
                    </form>
                    
                    <p class="text-muted small mt-3">
                        By subscribing, you agree to our Privacy Policy and consent to receive updates.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Brand Column -->
                <div class="footer-brand">
                    <a href="{{ route('home') }}" class="footer-logo">
                        <i class="bi bi-cart3"></i>
                        <span>LaraMart</span>
                    </a>
                    <p class="footer-description">
                        Your trusted online marketplace for quality products at affordable prices. 
                        We're committed to providing the best shopping experience.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">Shop</a></li>
                        <li><a href="{{ route('categories.index') }}">Categories</a></li>
                        <li><a href="{{ route('products.sale') }}">Sale</a></li>
                        <li><a href="{{ route('products.new') }}">New Arrivals</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div>
                    <h4 class="footer-heading">Customer Service</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="{{ route('shipping') }}">Shipping Policy</a></li>
                        <li><a href="{{ route('returns') }}">Returns & Refunds</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- My Account -->
                <div>
                    <h4 class="footer-heading">My Account</h4>
                    <ul class="footer-links">
                        @auth
                            <li><a href="{{ route('profile.show') }}">My Profile</a></li>
                            <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                            <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                            <li><a href="{{ route('addresses.index') }}">Address Book</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="footer-newsletter">
                    <h4 class="footer-heading">Newsletter</h4>
                    <p class="footer-description">
                        Subscribe to get special offers, free giveaways, and once-in-a-lifetime deals.
                    </p>
                    <form class="newsletter-form">
                        <input type="email" 
                               class="newsletter-input" 
                               placeholder="Enter your email">
                        <button type="submit" class="btn btn-primary newsletter-btn">
                            <i class="bi bi-send"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <div>
                    &copy; {{ date('Y') }} Laravel Mart. All rights reserved.
                </div>
                <div class="footer-payment">
                    <div class="payment-icon">VISA</div>
                    <div class="payment-icon">MC</div>
                    <div class="payment-icon">PP</div>
                    <div class="payment-icon">GP</div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            // User dropdown
            const userAvatar = document.querySelector('.user-avatar');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (userAvatar && dropdownMenu) {
                userAvatar.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    dropdownMenu.classList.remove('show');
                });
            }
            
            // Product filtering
            const filterButtons = document.querySelectorAll('.filter-btn');
            const productCards = document.querySelectorAll('.product-card');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    const filter = this.dataset.filter;
                    
                    // Filter products
                    productCards.forEach(card => {
                        if (filter === 'all' || card.dataset.category === filter) {
                            card.style.display = 'block';
                            card.classList.add('fade-in');
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
            
            // Wishlist toggle
            document.querySelectorAll('.product-wishlist').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    const heartIcon = this.querySelector('i');
                    
                    // Toggle heart icon
                    if (heartIcon.classList.contains('bi-heart-fill')) {
                        heartIcon.classList.remove('bi-heart-fill');
                        heartIcon.classList.add('bi-heart');
                        showToast('Removed from wishlist', 'success');
                    } else {
                        heartIcon.classList.remove('bi-heart');
                        heartIcon.classList.add('bi-heart-fill');
                        showToast('Added to wishlist', 'success');
                    }
                    
                    // Send AJAX request
                    fetch(`/wishlist/${productId}/toggle`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                      .then(data => {
                          if (data.error) {
                              showToast(data.error, 'error');
                          }
                      });
                });
            });
            
            // Add to cart with AJAX
            document.querySelectorAll('form[action*="cart.add"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.innerHTML;
                    
                    // Show loading state
                    button.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Adding...';
                    button.disabled = true;
                    
                    // Submit form via AJAX
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => response.json())
                      .then data => {
                          if (data.success) {
                              // Update cart count
                              const cartBadge = document.querySelector('.cart-badge');
                              if (cartBadge) {
                                  cartBadge.textContent = data.cart_count;
                              } else {
                                  // Create badge if doesn't exist
                                  const cartLink = document.querySelector('.cart-link');
                                  if (cartLink) {
                                      const badge = document.createElement('span');
                                      badge.className = 'cart-badge';
                                      badge.textContent = data.cart_count;
                                      cartLink.appendChild(badge);
                                  }
                              }
                              
                              showToast(data.message, 'success');
                          } else {
                              showToast(data.error || 'Something went wrong', 'error');
                          }
                      })
                      .catch(error => {
                          showToast('Network error. Please try again.', 'error');
                      })
                      .finally(() => {
                          // Reset button
                          button.innerHTML = originalText;
                          button.disabled = false;
                      });
                });
            });
            
            // Toast notification function
            function showToast(message, type = 'info') {
                const container = document.querySelector('.toast-container');
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                
                const icons = {
                    success: 'bi-check-circle-fill',
                    error: 'bi-x-circle-fill',
                    warning: 'bi-exclamation-triangle-fill',
                    info: 'bi-info-circle-fill'
                };
                
                toast.innerHTML = `
                    <i class="bi ${icons[type]} toast-icon text-${type}"></i>
                    <div class="toast-content">
                        <div class="toast-title">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                        <div class="toast-message">${message}</div>
                    </div>
                    <button class="toast-close">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                
                container.appendChild(toast);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.remove();
                }, 5000);
                
                // Close button
                toast.querySelector('.toast-close').addEventListener('click', () => {
                    toast.remove();
                });
            }
            
            // Countdown timer
            function updateCountdown() {
                const elements = {
                    hours: document.querySelector('.countdown-hours'),
                    minutes: document.querySelector('.countdown-minutes'),
                    seconds: document.querySelector('.countdown-seconds')
                };
                
                if (!elements.hours) return;
                
                let hours = parseInt(elements.hours.textContent);
                let minutes = parseInt(elements.minutes.textContent);
                let seconds = parseInt(elements.seconds.textContent);
                
                seconds--;
                
                if (seconds < 0) {
                    seconds = 59;
                    minutes--;
                    
                    if (minutes < 0) {
                        minutes = 59;
                        hours--;
                        
                        if (hours < 0) {
                            hours = 0;
                            minutes = 0;
                            seconds = 0;
                            clearInterval(countdownInterval);
                        }
                    }
                }
                
                elements.hours.textContent = hours.toString().padStart(2, '0');
                elements.minutes.textContent = minutes.toString().padStart(2, '0');
                elements.seconds.textContent = seconds.toString().padStart(2, '0');
            }
            
            // Start countdown if exists
            const countdownInterval = setInterval(updateCountdown, 1000);
            
            // Mobile menu toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navbarActions = document.querySelector('.navbar-actions');
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', () => {
                    navbarActions.classList.toggle('show');
                });
            }
        });
        
        // Add CSS for spinner
        const style = document.createElement('style');
        style.textContent = `
            .spin {
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            @media (max-width: 768px) {
                .navbar-actions {
                    position: fixed;
                    top: var(--header-height);
                    left: 0;
                    right: 0;
                    background: white;
                    border-top: 1px solid var(--neutral-200);
                    padding: 1rem;
                    flex-direction: column;
                    gap: 0.5rem;
                    display: none;
                }
                
                .navbar-actions.show {
                    display: flex;
                }
                
                .navbar-actions .navbar-link {
                    justify-content: flex-start;
                    width: 100%;
                }
                
                .user-dropdown .dropdown-menu {
                    position: static;
                    box-shadow: none;
                    border: none;
                    padding: 0.5rem 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>