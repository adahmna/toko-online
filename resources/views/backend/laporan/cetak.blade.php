<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $judul }}</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    .kop {
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 3px solid #1976d2;
        padding-bottom: 14px;
        margin-bottom: 14px;
    }

    .kop img {
        width: 80px;
        height: 80px;
        object-fit: contain;
    }

    .kop .nama-toko {
        font-size: 22px;
        font-weight: bold;
        text-transform: uppercase;
        margin: 0 0 4px;
    }

    .kop .alamat-toko {
        font-size: 13px;
        color: #555;
        margin: 0;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ccc;
    }

    table tr td {
        padding: 6px;
        font-weight: normal;
        border: 1px solid #ccc;
    }

    table th {
        border: 1px solid #ccc;
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }
    </style>
</head>

<body>

    {{-- ===== KOP: LOGO, NAMA TOKO, ALAMAT TOKO ===== --}}
    <div class="kop">
        <img src="{{ asset('image/logo.png') }}" alt="Logo">
        <div>
            <p class="nama-toko">BSI CYBER STORE</p>
            <p class="alamat-toko">
                Jl. Kramat Raya No.98 RT.02/RW.09, Kramat, Senen, Kota Jakarta Pusat,
                Daerah Khusus Ibukota Jakarta 10450
            </p>
        </div>
    </div>

    <table>
        <tr>
            <td class="text-left">
                Perihal : {{ $judul }} <br>
                Tanggal Awal: {{ $tanggalAwal }} s/d Tanggal Akhir: {{ $tanggalAkhir }} <br>
                Dicetak oleh: <strong>{{ auth()->user()->nama ?? '-' }}</strong> <br>
                Dicetak pada: <strong id="waktu-cetak">-</strong>
            </td>
        </tr>
    </table>

    <p></p>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th>Nama Produk</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Waktu</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-center">Jumlah Terjual</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse ($cetak as $row)
            @php $grandTotal += $row->subtotal; @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $row->nama_produk }}</td>
                {{-- Isi awal dari server; diganti JS sesuai zona waktu perangkat --}}
                <td class="text-center tgl-jual" data-waktu="{{ $row->created_at->toIso8601String() }}">
                    {{ $row->created_at->format('d-m-Y') }}
                </td>
                <td class="text-center jam-jual" data-waktu="{{ $row->created_at->toIso8601String() }}">
                    {{ $row->created_at->format('H:i') }}
                </td>
                <td class="text-right">Rp. {{ number_format($row->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ $row->jumlah }}</td>
                <td class="text-right">Rp. {{ number_format($row->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data produk terjual pada periode ini.</td>
            </tr>
            @endforelse
            @if($cetak->count() > 0)
            <tr>
                <td colspan="6" class="text-right"><strong>Grand Total</strong></td>
                <td class="text-right"><strong>Rp. {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>

    <script>
    // Label zona waktu perangkat: WIB / WITA / WIT (selain itu pakai nama zona)
    function labelZona(date) {
        const offset = -date.getTimezoneOffset() / 60;
        return {
            7: 'WIB',
            8: 'WITA',
            9: 'WIT'
        } [offset] || Intl.DateTimeFormat().resolvedOptions().timeZone;
    }

    function formatJam(date) {
        return date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        }).replace('.', ':') + ' ' + labelZona(date);
    }

    function formatTanggalPendek(date) {
        const dd = String(date.getDate()).padStart(2, '0');
        const mm = String(date.getMonth() + 1).padStart(2, '0');
        return dd + '-' + mm + '-' + date.getFullYear();
    }

    window.onload = function() {
        // Waktu cetak (sekarang) sesuai perangkat
        const now = new Date();
        const hariCetak = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
        document.getElementById('waktu-cetak').textContent = hariCetak + ', ' + formatJam(now);

        // Tanggal & waktu penjualan tiap baris sesuai perangkat
        document.querySelectorAll('.tgl-jual').forEach(function(el) {
            el.textContent = formatTanggalPendek(new Date(el.dataset.waktu));
        });

        document.querySelectorAll('.jam-jual').forEach(function(el) {
            el.textContent = formatJam(new Date(el.dataset.waktu));
        });

        printStruk();
    };

    function printStruk() {
        window.print();
    }
    </script>

</body>

</html>