<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pesanan->status === 'Paid' ? 'Pembayaran Berhasil' : 'Status Pesanan' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @if (!in_array($pesanan->status, ['Paid', 'Kadaluarsa', 'Dibatalkan']))
    <meta http-equiv="refresh" content="10">
    @endif
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card shadow-sm border-0 p-5">

                    @if ($pesanan->status === 'Paid')
                    {{-- ===== SUDAH DIBAYAR ===== --}}
                    <div class="mb-4">
                        <span class="display-1 text-success">&check;</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">Pembayaran Berhasil! 🎉</h2>
                    <p class="text-muted mb-4">Terima kasih, pesananmu sedang kami proses.</p>

                    @elseif ($pesanan->status === 'Kadaluarsa')
                    {{-- ===== KADALUARSA ===== --}}
                    <div class="mb-4">
                        <span class="display-1 text-danger">&times;</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">Waktu Pembayaran Habis</h2>
                    <p class="text-muted mb-4">Pesanan ini sudah kadaluarsa karena tidak dibayar tepat waktu.
                        Silakan buat pesanan baru.</p>

                    @elseif ($pesanan->status === 'Dibatalkan')
                    {{-- ===== DIBATALKAN ===== --}}
                    <div class="mb-4">
                        <span class="display-1 text-danger">&times;</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">Pesanan Dibatalkan</h2>
                    <p class="text-muted mb-4">Pembayaran untuk pesanan ini dibatalkan atau ditolak.</p>

                    @else
                    {{-- ===== MASIH MENUNGGU PEMBAYARAN ===== --}}
                    <div class="mb-4">
                        <div class="spinner-border text-warning" style="width:3.5rem;height:3.5rem;" role="status">
                            <span class="visually-hidden">Menunggu...</span>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">Selesaikan Pembayaranmu</h2>
                    <p class="text-muted mb-4">Pesanan sudah dibuat, tapi pembayaran belum kami terima. Halaman ini
                        akan otomatis update begitu pembayaranmu terkonfirmasi.</p>
                    @endif

                    <div class="bg-white border rounded p-3 mb-4 text-start">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><strong>ID Pesanan:</strong> #{{ $pesanan->id }}</li>
                            <li class="mb-2"><strong>Kode Pesanan:</strong> <span
                                    class="font-monospace text-primary">{{ $pesanan->kode_pesanan }}</span></li>
                            <li class="mb-2"><strong>Total Bayar:</strong> Rp
                                {{ number_format($pesanan->total_harga, 0, ',', '.') }}</li>
                            <li>
                                <strong>Status:</strong>
                                @php
                                $badgeClass = match($pesanan->status) {
                                'Paid' => 'bg-success',
                                'Kadaluarsa', 'Dibatalkan' => 'bg-danger',
                                default => 'bg-warning text-dark',
                                };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $pesanan->status }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        @if (!in_array($pesanan->status, ['Paid', 'Kadaluarsa', 'Dibatalkan']))
                        <a href="{{ route('customer.checkout.pembayaran', $pesanan->id) }}"
                            class="btn btn-warning btn-lg">Bayar Sekarang</a>
                        @endif
                        <a href="{{ route('beranda') }}" class="btn btn-primary btn-lg">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
