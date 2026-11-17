<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Password Baru | BSI CYBER STORE</title>
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
        margin-bottom: 28px;
        font-size: 0.95rem;
    }

    label.field-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #52483f;
        margin-bottom: 6px;
    }

    .field-wrap {
        position: relative;
        margin-bottom: 18px;
    }

    .field-wrap i.leading {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #c9beb3;
        font-size: 0.95rem;
    }

    .field-wrap input {
        width: 100%;
        padding: 12px 14px 12px 40px;
        border: 1.5px solid #eee1d8;
        border-radius: 10px;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.15s ease;
    }

    .field-wrap input:focus {
        border-color: #f2541c;
    }

    .field-wrap .toggle-eye {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #c9beb3;
        font-size: 0.9rem;
    }

    .field-error {
        color: #e0392b;
        font-size: 0.8rem;
        margin-top: 4px;
        display: block;
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
            <h1>Hampir selesai,<br>buat password baru.</h1>
            <p>Kode OTP kamu sudah terverifikasi. Sekarang buat password baru
                yang aman untuk akun kamu.</p>

            <div class="brand-cart">
                <div class="bag"><i class="fa-solid fa-lock"></i></div>
                <div class="txt">
                    <strong>Minimal 6 karakter</strong>
                    <small>gunakan kombinasi yang kuat</small>
                </div>
            </div>
        </div>

        <div class="form-panel">
            <div class="login-card">

                <h2>Buat Password Baru</h2>
                <p class="sub">Password baru akan langsung berlaku untuk login berikutnya.</p>

                @if($errors->any())
                <div class="alert-box">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('customer.reset-password.post') }}" method="POST">
                    @csrf
                    <div class="field-wrap">
                        <label class="field-label">Password Baru</label>
                        <i class="fa-solid fa-lock leading"></i>
                        <input type="password" name="password" id="password_field" placeholder="Masukkan password baru">
                        <i class="fa-solid fa-eye toggle-eye" id="eye_icon" onclick="togglePw('password_field', 'eye_icon')"></i>
                    </div>

                    <div class="field-wrap">
                        <label class="field-label">Konfirmasi Password</label>
                        <i class="fa-solid fa-lock leading"></i>
                        <input type="password" name="password_confirmation" id="password_confirm_field"
                            placeholder="Ulangi password baru">
                        <i class="fa-solid fa-eye toggle-eye" id="eye_icon2"
                            onclick="togglePw('password_confirm_field', 'eye_icon2')"></i>
                    </div>

                    <button type="submit" class="btn-submit">Simpan Password Baru</button>
                </form>
            </div>
        </div>

    </div>

    <script>
    function togglePw(fieldId, iconId) {
        const f = document.getElementById(fieldId);
        const i = document.getElementById(iconId);
        if (f.type === 'password') {
            f.type = 'text';
            i.classList.remove('fa-eye');
            i.classList.add('fa-eye-slash');
        } else {
            f.type = 'password';
            i.classList.remove('fa-eye-slash');
            i.classList.add('fa-eye');
        }
    }
    </script>

</body>

</html>