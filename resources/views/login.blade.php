<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laravel Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-4 fw-bold">🔐 Login Dulu</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf <div class="mb-3">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="user@toko.com" required value="{{ old('email') }}">
            </div>
            
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold">MASUK</button>
        </form>

        <p class="text-center mt-3 text-muted">
            Belum punya akun? <a href="#">Daftar</a>
        </p>
    </div>

</body>
</html>