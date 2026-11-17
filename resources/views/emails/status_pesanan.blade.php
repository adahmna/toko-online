<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 30px auto;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        background: #1e2a3a;
        color: #fff;
        padding: 24px 30px;
        text-align: center;
    }

    .header h2 {
        margin: 0;
        font-size: 22px;
    }

    .header p {
        margin: 5px 0 0;
        font-size: 13px;
        color: #90caf9;
    }

    .body {
        padding: 30px;
    }

    .greeting {
        font-size: 16px;
        color: #333;
        margin-bottom: 16px;
    }

    .info-box {
        background: #f8f9fa;
        border-left: 4px solid #1976d2;
        border-radius: 4px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .info-box p {
        margin: 6px 0;
        font-size: 14px;
        color: #444;
    }

    .info-box b {
        color: #1e2a3a;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 14px;
    }

    .status-menunggu {
        background: #fff3cd;
        color: #856404;
    }

    .status-diproses {
        background: #cce5ff;
        color: #004085;
    }

    .status-dikirim {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-selesai {
        background: #d4edda;
        color: #155724;
    }

    .status-dibatalkan {
        background: #f8d7da;
        color: #721c24;
    }

    .resi-box {
        background: #e8f5e9;
        border: 1px dashed #4caf50;
        border-radius: 4px;
        padding: 12px 16px;
        margin-top: 12px;
    }

    .resi-box p {
        margin: 0;
        font-size: 14px;
        color: #2e7d32;
    }

    .footer {
        background: #f8f9fa;
        text-align: center;
        padding: 16px;
        font-size: 12px;
        color: #888;
        border-top: 1px solid #eee;
    }

    .btn {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 24px;
        background: #1976d2;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>BSI CYBER STORE</h2>
            <p>Notifikasi Update Pesanan</p>
        </div>
        <div class="body">
            <p class="greeting">Halo, <b>{{ $pesanan->user->nama }}</b>!</p>
            <p style="color:#555; font-size:14px;">Status pesanan kamu telah diperbarui. Berikut detail pesananmu:</p>

            <div class="info-box">
                <p><b>Kode Pesanan:</b> {{ $pesanan->kode_pesanan }}</p>
                <p><b>Total Harga:</b> Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                <p><b>Tanggal Pesanan:</b> {{ $pesanan->created_at->format('d M Y H:i') }}</p>
                <p><b>Status Terbaru:</b>
                    @php $status = strtolower($pesanan->status); @endphp
                    <span class="status-badge
                        @if($status == 'menunggu pembayaran') status-menunggu
                        @elseif($status == 'diproses') status-diproses
                        @elseif($status == 'dikirim') status-dikirim
                        @elseif($status == 'selesai') status-selesai
                        @else status-dibatalkan
                        @endif">
                        {{ $pesanan->status }}
                    </span>
                </p>
            </div>

            @if($pesanan->no_resi)
            <div class="resi-box">
                <p>🚚 <b>Nomor Resi Pengiriman:</b> {{ $pesanan->no_resi }}</p>
                <p style="margin-top:4px; font-size:12px;">Gunakan nomor resi ini untuk melacak paketmu.</p>
            </div>
            @endif

            <p style="font-size:13px; color:#777; margin-top:20px;">
                Jika ada pertanyaan, silakan hubungi kami. Terima kasih sudah berbelanja di BSI Cyber Store!
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BSI Cyber Store. All rights reserved.
        </div>
    </div>
</body>

</html>
