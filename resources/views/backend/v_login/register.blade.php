<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }} | BSI CYBER STORE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    {{-- Tambahan link Font Awesome agar ikon mata ter-render sempurna --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    body {
        background-color: #343a40;
        color: #fff;
    }

    .register-container {
        max-width: 500px;
        margin: 50px auto;
    }

    .card {
        background-color: #212529;
        border: 1px solid #454d55;
    }

    /* Kunci perbaikan tampilan password agar proporsional dan tidak mengecil */
    .password-container {
        position: relative;
        width: 100%;
        display: block;
    }

    .password-container input {
        padding-right: 45px !important;
        /* Memberi ruang di kanan dalam input agar teks tidak tertimpa ikon */
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #a1a1a1;
        z-index: 10;
        font-size: 16px;
    }
    </style>
</head>

<body>

    <div class="container">
        <div class="register-container">
            <div class="text-center mb-4">
                {{-- BERHASIL DIRAPIKAN: Memanggil logo utama agar gambarnya tajam dan bening --}}
                <img src="{{ asset('image/logo.png') }}" alt="Logo BSI" style="width: 160px; height: auto;">
                <h4 class="mt-2">BSI CYBER STORE</h4>
                <p class="text-muted">Buat akun baru kamu sekarang</p>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('backend.proses-register') }}" method="POST">
                        @csrf

                        @if (session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                        @endif
                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>No. Handphone <span class="text-danger">*</span></label>
                            <input type="text" name="hp" class="form-control @error('hp') is-invalid @enderror"
                                value="{{ old('hp') }}" onkeypress="return hanyaAngka(event)" required>
                            @error('hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Perbaikan kolom Password Utama --}}
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <div class="password-container">
                                <input type="password" name="password" id="password_reg"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan Password" required>
                                <i class="fa-solid fa-eye toggle-password" id="eye_reg"
                                    onclick="togglePasswordVisibility('password_reg', 'eye_reg')"></i>
                            </div>
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Perbaikan kolom Konfirmasi Password --}}
                        <div class="form-group">
                            <label>Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="password-container">
                                <input type="password" name="password_confirmation" id="password_confirm"
                                    class="form-control" placeholder="Ulangi Password" required>
                                <i class="fa-solid fa-eye toggle-password" id="eye_confirm"
                                    onclick="togglePasswordVisibility('password_confirm', 'eye_confirm')"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block shadow-sm mt-4">Daftar Sekarang</button>

                        <div class="text-center mt-2">
                            <small class="text-muted">Belum pernah melamar kerja? <a href="{{ route('karir.lamar') }}"
                                    class="text-warning">Lamar di sini dulu</a></small>
                        </div>

                        <div class="text-center mt-3">
                            <span class="text-muted">Sudah punya akun?</span> <a href="{{ route('backend.login') }}"
                                class="text-info">Login Di Sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function hanyaAngka(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    </script>

    <script>
    function togglePasswordVisibility(inputId, eyeId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(eyeId);

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
    </script>
</body>

</html>