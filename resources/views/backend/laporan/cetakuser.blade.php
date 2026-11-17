<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data User</title>
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

    .judul-laporan {
        text-align: center;
        margin-bottom: 16px;
    }

    .judul-laporan .judul {
        font-size: 16px;
        font-weight: bold;
    }

    .judul-laporan .info {
        font-size: 12px;
        color: #555;
        margin-top: 4px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ccc;
    }

    table tr td {
        padding: 8px;
        font-weight: normal;
        border: 1px solid #ccc;
    }

    table th {
        border: 1px solid #ccc;
        background-color: #f2f2f2;
        font-weight: bold;
        padding: 8px;
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

    <div class="judul-laporan">
        <div class="judul">Laporan Data User</div>
        <div class="info">
            Periode: {{ $dari }} s/d {{ $sampai }}<br>
            Dicetak pada: <strong id="waktu-cetak">-</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th width="15%" class="text-center">Tanggal Daftar</th>
                <th width="12%" class="text-center">Jam</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $row)
            @php
            $waktuDaftar = \Carbon\Carbon::parse($row->created_at);
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $row->nama ?? $row->name ?? '-' }}</td>
                <td>{{ $row->email }}</td>
                <td>
                    @if ($row->role == '0')
                    Admin
                    @elseif ($row->role == '1')
                    Super Admin
                    @elseif ($row->role == '2')
                    Customer
                    @else
                    {{ $row->role }}
                    @endif
                </td>
                {{-- Isi awal dari server; diganti JS sesuai zona waktu perangkat --}}
                <td class="text-center tgl-daftar" data-waktu="{{ $waktuDaftar->toIso8601String() }}">
                    {{ $waktuDaftar->format('d M Y') }}
                </td>
                <td class="text-center jam-daftar" data-waktu="{{ $waktuDaftar->toIso8601String() }}">
                    {{ $waktuDaftar->format('H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data user pada periode ini.</td>
            </tr>
            @endforelse
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

        // Tanggal & jam daftar tiap user sesuai perangkat
        document.querySelectorAll('.tgl-daftar').forEach(function(el) {
            const d = new Date(el.dataset.waktu);
            el.textContent = d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        });

        document.querySelectorAll('.jam-daftar').forEach(function(el) {
            el.textContent = formatJam(new Date(el.dataset.waktu));
        });

        window.print();
    };
    </script>

</body>

</html>