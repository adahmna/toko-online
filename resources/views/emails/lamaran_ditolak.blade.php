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
        border-left: 4px solid #e53935;
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
        background: #f8d7da;
        color: #721c24;
    }

    .catatan-box {
        background: #fff8f8;
        border: 1px dashed #e53935;
        border-radius: 4px;
        padding: 12px 16px;
        margin-top: 12px;
    }

    .catatan-box p {
        margin: 0;
        font-size: 14px;
        color: #b71c1c;
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
            <h2>BSI CYBER STORE</h2>
            <p>Notifikasi Lamaran Kerja</p>
        </div>
        <div class="body">
            <p class="greeting">Halo, <b>{{ $lamaran->nama }}</b>.</p>
            <p style="color:#555; font-size:14px;">
                Terima kasih atas ketertarikanmu untuk bergabung dengan tim BSI Cyber Store.
                Setelah melalui proses peninjauan, kami mohon maaf harus menyampaikan bahwa
                lamaranmu belum dapat kami terima saat ini.
            </p>

            <div class="info-box">
                <p><b>Status:</b> <span class="status-badge">Belum Diterima</span></p>
            </div>

            @if($lamaran->catatan_admin)
            <div class="catatan-box">
                <p><b>Catatan dari Tim HRD:</b></p>
                <p style="margin-top:6px;">{{ $lamaran->catatan_admin }}</p>
            </div>
            @endif

            <p style="font-size:13px; color:#777; margin-top:24px;">
                Jangan berkecil hati — kamu tetap bisa mencoba melamar kembali di kesempatan berikutnya.
                Terima kasih dan semoga sukses selalu!
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BSI Cyber Store. All rights reserved.
        </div>
    </div>
</body>

</html>