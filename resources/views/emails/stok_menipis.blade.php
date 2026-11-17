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
        background: #e53935;
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
        color: #ffcdd2;
    }

    .body {
        padding: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    th {
        background: #f5f5f5;
        padding: 10px;
        text-align: left;
        font-size: 13px;
        border-bottom: 2px solid #ddd;
    }

    td {
        padding: 10px;
        font-size: 13px;
        border-bottom: 1px solid #eee;
    }

    .stok-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-weight: bold;
        font-size: 12px;
    }

    .stok-habis {
        background: #ffebee;
        color: #c62828;
    }

    .stok-menipis {
        background: #fff3e0;
        color: #e65100;
    }

    .footer {
        background: #f8f9fa;
        text-align: center;
        padding: 16px;
        font-size: 12px;
        color: #888;
        border-top: 1px solid #eee;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>⚠️ BSI CYBER STORE</h2>
            <p>Peringatan Stok Produk Menipis</p>
        </div>
        <div class="body">
            <p style="color:#333; font-size:15px;">Halo Admin,</p>
            <p style="color:#555; font-size:13px;">Berikut daftar produk yang stoknya sudah menipis dan perlu segera
                diisi ulang:</p>

            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produkMenipis as $produk)
                    <tr>
                        <td>{{ $produk->nama_produk }}</td>
                        <td>{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                        <td>
                            <span class="stok-badge {{ $produk->stok == 0 ? 'stok-habis' : 'stok-menipis' }}">
                                {{ $produk->stok == 0 ? 'Habis' : $produk->stok . ' tersisa' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p style="margin-top:20px; font-size:13px; color:#777;">
                Segera lakukan penambahan stok untuk menghindari kehabisan produk.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BSI Cyber Store. All rights reserved.
        </div>
    </div>
</body>

</html>
