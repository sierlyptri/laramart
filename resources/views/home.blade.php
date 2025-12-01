<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">🛒 Laravel Mart</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">Produk Terbaru</h2>
        
        <div class="row">
            @foreach($products as $item)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="...">
                    
                    <div class="card-body">
                        <small class="text-muted">{{ $item->category->name }}</small>
                        <h5 class="card-title mt-1">{{ $item->name }}</h5>
                        <p class="card-text text-primary fw-bold">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </p>
                        <a href="#" class="btn btn-outline-primary w-100">Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</body>
</html>