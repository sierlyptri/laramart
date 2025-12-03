<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5 mb-5">
        <div class="card shadow">
            <div class="card-body p-5">
                
                <div class="text-center mb-5">
                    <h1 class="text-success" style="font-size: 4rem;">✅</h1>
                    <h2 class="fw-bold">Order Berhasil Dibuat!</h2>
                    <p class="text-muted">Terima kasih sudah berbelanja di Laravel Mart.</p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Detail Pemesan:</h5>
                        <p>
                            <strong>Nama:</strong> {{ $order->user->name }}<br>
                            <strong>Email:</strong> {{ $order->user->email }}<br>
                            <strong>Alamat Pengiriman:</strong><br> 
                            {{ $order->shipping_address }}
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h5>No. Invoice:</h5>
                        <h4 class="fw-bold text-primary">{{ $order->code }}</h4>
                        <p class="text-muted">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                        <span class="badge bg-warning text-dark">Status: {{ strtoupper($order->status) }}</span>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">TOTAL BAYAR</td>
                            <td class="text-end fw-bold fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-center mt-5">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">⬅ Kembali Belanja</a>
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-lg">🖨 Cetak Invoice</button>
                </div>

            </div>
        </div>
    </div>

</body>
</html>