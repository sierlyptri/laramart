<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Laravel Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">⬅ Kembali ke Home</a>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <img src="https://via.placeholder.com/500" class="img-fluid rounded shadow-sm" alt="...">
            </div>

            <div class="col-md-7">
                <h1 class="fw-bold">{{ $product->name }}</h1>
                <p class="text-muted">Kategori: {{ $product->category->name }}</p>
                
                <h3 class="text-primary fw-bold mb-4">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </h3>

                <p class="fs-5">{{ $product->description }}</p>

                <hr class="my-4">

                <div class="d-flex gap-3">
                    <button class="btn btn-outline-secondary">− Kurang</button>
                    <input type="number" value="1" class="form-control text-center" style="width: 60px;">
                    <button class="btn btn-outline-secondary">Tambah +</button>
                    
                    <button class="btn btn-primary w-100 fw-bold">🛒 Masukkan Keranjang</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>