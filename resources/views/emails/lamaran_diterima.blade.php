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
        border-left: 4px solid #43a047;
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
        background: #d4edda;
        color: #155724;
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
        background: #43a047;
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
            <p>Notifikasi Lamaran Kerja</p>
        </div>
        <div class="body">
            <p class="greeting">Halo, <b>{{ $lamaran->nama }}</b>!</p>
            <p style="color:#555; font-size:14px;">
                Selamat! Lamaran kerjamu untuk bergabung sebagai admin di BSI Cyber Store telah
                <b>diterima</b> oleh tim HRD kami.
            </p>

            <div class="info-box">
                <p><b>Status:</b> <span class="status-badge">Diterima</span></p>
                <p><b>Email Terdaftar:</b> {{ $lamaran->email }}</p>
            </div>

            <p style="font-size:14px; color:#444;">
                Langkah selanjutnya, silakan lanjutkan proses dengan mendaftarkan akun admin
                menggunakan email yang sama ({{ $lamaran->email }}) melalui link di bawah ini:
            </p>

            <div style="text-align:center;">
                <a href="{{ url('/backend/register') }}" class="btn">Daftar Akun Admin</a>
            </div>

            <p style="font-size:13px; color:#777; margin-top:24px;">
                Jika tombol di atas tidak berfungsi, salin dan buka link berikut di browser:<br>
                {{ url('/backend/register') }}
            </p>

            <p style="font-size:13px; color:#777; margin-top:20px;">
                Selamat bergabung, dan terima kasih atas ketertarikanmu berkarir di BSI Cyber Store!
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BSI Cyber Store. All rights reserved.
        </div>
    </div>
</body>

</html>