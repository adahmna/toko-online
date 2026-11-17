<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Kode OTP | BSI CYBER STORE</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon_univ_bsi.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
        background: #fff5ef;
    }

    .login-shell {
        min-height: 100vh;
        display: flex;
    }

    .brand-panel {
        flex: 1 1 46%;
        position: relative;
        background: linear-gradient(155deg, #ff7a3d 0%, #f2541c 55%, #d63c0e 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px 64px;
        overflow: hidden;
    }

    .brand-panel::before,
    .brand-panel::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .brand-panel::before {
        width: 340px;
        height: 340px;
        top: -120px;
        right: -100px;
    }

    .brand-panel::after {
        width: 220px;
        height: 220px;
        bottom: -80px;
        left: -60px;
    }

    .brand-logo {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        margin-bottom: 32px;
        position: relative;
        z-index: 1;
    }

    .brand-panel h1 {
        font-size: 2.3rem;
        font-weight: 800;
        line-height: 1.25;
        margin: 0 0 18px;
        position: relative;
        z-index: 1;
        max-width: 420px;
    }

    .brand-panel p {
        font-size: 1.02rem;
        opacity: 0.92;
        max-width: 380px;
        position: relative;
        z-index: 1;
    }

    .brand-cart {
        position: relative;
        z-index: 1;
        margin-top: 48px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .brand-cart .bag {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .brand-cart .txt small {
        display: block;
        opacity: 0.8;
        font-size: 0.8rem;
    }

    .brand-cart .txt strong {
        font-size: 1.05rem;
    }

    .form-panel {
        flex: 1 1 54%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 24px;
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        opacity: 0;
        transform: translateY(18px);
        animation: card-in 0.55s ease forwards;
    }

    @keyframes card-in {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-card h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #26201c;
        margin-bottom: 6px;
    }

    .login-card .sub {
        color: #8a8078;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    .login-card .sub b {
        color: #26201c;
    }

    .otp-input {
        width: 100%;
        padding: 16px;
        border: 1.5px solid #eee1d8;
        border-radius: 10px;
        font-size: 1.6rem;
        letter-spacing: 12px;
        text-align: center;
        font-weight: 700;
        color: #26201c;
        outline: none;
        transition: border-color 0.15s ease;
        margin-bottom: 18px;
    }

    .otp-input:focus {
        border-color: #f2541c;
    }

    .field-error {
        color: #e0392b;
        font-size: 0.8rem;
        margin-top: -12px;
        margin-bottom: 18px;
        display: block;
        text-align: center;
    }

    .btn-submit {
        width: 100%;
        padding: 13px 16px;
        border: none;
        border-radius: 10px;
        background: #f2541c;
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.15s ease;
    }

    .btn-submit:hover {
        background: #d8430f;
        transform: translateY(-1px);
    }

    .alert-box {
        background: #fde8e4;
        color: #b3271b;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.88rem;
        margin-bottom: 18px;
    }

    .alert-box.success {
        background: #e5f6ea;
        color: #1e7d38;
    }

    .foot-link {
        text-align: center;
        margin-top: 26px;
        font-size: 0.9rem;
        color: #8a8078;
    }

    .foot-link button {
        background: none;
        border: none;
        color: #f2541c;
        font-weight: 700;
        cursor: pointer;
        font-size: 0.9rem;
        padding: 0;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #8a8078;
        text-decoration: none;
        font-size: 0.85rem;
        margin-bottom: 22px;
    }

    .back-link:hover {
        color: #f2541c;
    }

    @media (max-width: 860px) {
        .brand-panel {
            display: none;
        }

        .form-panel {
            flex: 1 1 100%;
        }
    }
    </style>
</head>

<body>

    <div class="login-shell">

        <div class="brand-panel">
            <img src="{{ asset('image/logo.png') }}" alt="logo BSI" class="brand-logo">
            <h1>Cek email kamu,<br>kode sudah terkirim.</h1>
            <p>Masukkan 6 digit kode OTP yang kami kirimkan ke email kamu untuk
                melanjutkan proses reset password.</p>

            <div class="brand-cart">
                <div class="bag"><i class="fa-solid fa-envelope-open-text"></i></div>
                <div class="txt">
                    <strong>Kode 6 digit</strong>
                    <small>cek folder spam jika belum masuk</small>
                </div>
            </div>
        </div>

        <div class="form-panel">
            <div class="login-card">

                <a href="{{ route('customer.forgot-password') }}" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Ganti Email
                </a>

                <h2>Verifikasi Kode OTP</h2>
                <p class="sub">Kode dikirim ke <b>{{ $email }}</b></p>

                @if(session()->has('success'))
                <div class="alert-box success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('customer.verify-otp.post') }}" method="POST">
                    @csrf
                    <input type="text" name="otp" class="otp-input" maxlength="6" inputmode="numeric"
                        pattern="[0-9]*" placeholder="------" autofocus>
                    @error('otp')
                    <span class="field-error">{{ $message }}</span>
                    @enderror

                    <button type="submit" class="btn-submit">Verifikasi Kode</button>
                </form>

                <p class="foot-link">
                    Tidak menerima kode?
                    <form action="{{ route('customer.forgot-password.resend') }}" method="POST"
                        style="display:inline;">
                        @csrf
                        <button type="submit">Kirim ulang</button>
                    </form>
                </p>
            </div>
        </div>

    </div>

    <script>
    document.querySelector('.otp-input').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    </script>

</body>

</html>