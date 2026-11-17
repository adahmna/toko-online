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

    .header-table {
        margin-bottom: 20px;
    }

    .header-title {
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
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

    <table class="header-table">
        <tr>
            <td class="text-center" style="border: none;">
                <div class="header-title">BSI CYBER STORE</div>
                <div style="margin-top: 5px;">Perihal : {{ $judul }}</div>
                <div style="font-size: 12px; color: #555;">Tanggal Awal: {{ $tanggalAwal }} s/d Tanggal Akhir:
                    {{ $tanggalAkhir }}</div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th width="15%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cetak as $row)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $row->nama ?? $row->name ?? '-' }}</td>
                <td>{{ $row->email }}</td>
                <td>
                    @php
                    $roleText = trim(strtolower($row->role ?? ''));
                    @endphp

                    @if ($roleText == '2' || $roleText == 'customer')
                    Customer
                    @elseif ($roleText == '1' || $roleText == 'admin')
                    Admin
                    @elseif ($roleText == 'super admin' || $roleText == 'superadmin' || $row->role == '0')
                    Super Admin
                    @else
                    {{ ucfirst($row->role ?? 'Customer') }}
                    @endif
                </td>
                <td class="text-center">{{ $row->status ?? 'Aktif' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data user pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <script>
    window.onload = function() {
        window.print();
    }
    </script>

</body>

</html>
