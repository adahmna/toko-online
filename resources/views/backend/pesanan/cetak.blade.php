<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Pesanan #{{ $item->id }}</title>
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #222;
        margin: 20px;
    }

    .kop {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        border-bottom: 3px double #222;
        padding-bottom: 12px;
        margin-bottom: 12px;
    }

    .kop img {
        height: 70px;
        width: auto;
    }

    .kop .info {
        text-align: left;
    }

    .kop h2 {
        margin: 0;
        font-size: 24px;
    }

    .kop p {
        margin: 2px 0;
        font-size: 12px;
        color: #444;
    }

    .judul {
        text-align: center;
        margin: 8px 0 16px;
    }

    .judul h3 {
        margin: 0;
        font-size: 16px;
        font-weight: normal;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }

    .box td {
        border: 1px solid #ccc;
        padding: 8px 10px;
        vertical-align: top;
        width: 50%;
    }

    .tgl td {
        border: 1px solid #ccc;
        padding: 6px 10px;
    }

    .items th,
    .items td {
        border: 1px solid #ccc;
        padding: 8px 10px;
    }

    .items th {
        background: #f1f1f1;
    }

    .r {
        text-align: right;
    }

    .c {
        text-align: center;
    }

    .b {
        font-weight: bold;
    }

    @media print {
        body {
            margin: 0;
        }

        @page {
            margin: 12mm;
        }
    }
    </style>
</head>

<body>
    @php
    $user = $item->user;
    $namaUser = $user->nama ?? $user->name ?? '-';
    $hpUser = $user->hp ?? $user->no_hp ?? $user->telepon ?? '-';

    $subtotal = 0;
    foreach ($item->details as $d) {
    $harga = $d->harga ?? ($d->produk->harga ?? 0);
    $qty = $d->qty ?? $d->jumlah ?? 1;
    $subtotal += $harga * $qty;
    }
    $ongkir = $item->ongkir ?? $item->ongkos_kirim ?? 0;
    $total = $item->total_bayar ?? $item->total ?? ($subtotal + $ongkir);
    @endphp

    {{-- KOP: LOGO + ALAMAT TOKO --}}
    <div class="kop">
        <img src="{{ asset('image/logo.png') }}" alt="Logo BSI Cyber Store">
        <div class="info">
            <h2>BSI CYBER STORE</h2>
            <p>Jl. Kramat Raya No. 98, Senen, Jakarta Pusat 10450</p>
            <p>Telp: 021-1234567 | Email: cs@bsicyberstore.com</p>
        </div>
    </div>

    <div class="judul">
        <h3>Bukti Pesanan #{{ $item->id }}</h3>
    </div>

    <table class="box">
        <tr>
            <td>
                <div class="b">Pelanggan</div>
                Nama : {{ $namaUser }}<br>
                Email : {{ $user->email ?? '-' }}<br>
                Hp : {{ $hpUser }}
            </td>
            <td>
                <div class="b">Pengiriman</div>
                Kurir : {{ $item->ekspedisi ?: '-' }}<br>
                Layanan : {{ $item->layanan_ekspedisi ?: '-' }}<br>
                No. Resi : {{ $item->no_resi ?: '-' }}<br>
                Alamat : {{ $item->alamat }} {{ $item->kode_pos }}
            </td>
        </tr>
    </table>

    {{-- Tanggal ikut zona waktu perangkat (via JS) --}}
    <table class="tgl">
        <tr>
            <td>
                Tanggal Pesanan:
                <span id="tgl-pesanan" data-waktu="{{ $item->created_at->toIso8601String() }}">
                    {{ $item->created_at->format('d M Y H:i') }}
                </span>
            </td>
            <td class="r">Status: {{ ucfirst($item->status) }}</td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width:50px">No</th>
                <th>Nama Produk</th>
                <th class="r">Harga</th>
                <th class="c">Qty</th>
                <th class="r">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($item->details as $i => $d)
            @php
            $harga = $d->harga ?? ($d->produk->harga ?? 0);
            $qty = $d->qty ?? $d->jumlah ?? 1;
            @endphp
            <tr>
                <td class="c">{{ $i + 1 }}</td>
                <td>{{ $d->produk->nama_produk ?? $d->produk->nama ?? '-' }}</td>
                <td class="r">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                <td class="c">{{ $qty }}</td>
                <td class="r">Rp {{ number_format($harga * $qty, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="r b">Subtotal</td>
                <td class="r">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="r b">Ongkos Kirim</td>
                <td class="r">Rp {{ number_format($ongkir, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="r b">TOTAL BAYAR</td>
                <td class="r b">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <script>
    (function() {
        var el = document.getElementById('tgl-pesanan');
        var d = new Date(el.dataset.waktu);
        if (isNaN(d)) return;

        var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        var teks = new Intl.DateTimeFormat('id-ID', {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
            timeZone: tz,
            timeZoneName: 'short'
        }).format(d);

        el.textContent = teks.replace(/\./g, ':');
    })();
    </script>
</body>

</html>