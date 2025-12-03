<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Laravel Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/style.css'])
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <span style="font-size: 1.5rem;">🛒</span> Laravel Mart
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto d-flex align-items-center gap-3">
                    <a href="{{ route('home') }}" class="nav-link text-white">Home</a>
                    <a href="{{ route('cart.index') }}" class="btn btn-light position-relative rounded-circle p-2">
                        <i class="bi bi-cart-fill text-primary fs-5"></i>
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                        @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div style="height: 100px;"></div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <a href="{{ route('home') }}" class="text-decoration-none text-muted mb-3 d-inline-block">
                    <i class="bi bi-arrow-left"></i> Kembali ke Home
                </a>

                <div class="card shadow border-0 rounded-4">
                    <div class="card-body p-5">
                        
                        <div class="text-center mb-4">
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold mx-auto mb-3 shadow" 
                                 style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <h3 class="fw-bold">Edit Profile</h3>
                            <p class="text-muted">Perbarui informasi akunmu di sini</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center gap-2 rounded-3">
                                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT') <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control form-control-lg bg-light" 
                                       value="{{ old('name', Auth::user()->name) }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg bg-light" 
                                       value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Password Baru <span class="fw-normal text-danger" style="font-size: 0.8rem;">(Opsional)</span></label>
                                <input type="password" name="password" class="form-control form-control-lg bg-light" 
                                       placeholder="Kosongkan jika tidak ingin mengganti">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 mt-3 shadow-sm">
                                <i class="bi bi-save me-2"></i> Simpan Perubahan
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>