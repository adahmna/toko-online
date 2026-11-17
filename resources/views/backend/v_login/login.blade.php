<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    {{-- Favicon browser tetap pakai icon kecil --}}
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon_univ_bsi.png') }}">
    <title>BSI CYBER STORE | Login</title>

    {{-- 1. LINK CDN FONT AWESOME --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- 2. LINK THEMIFY ICONS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">

    <link href="{{ asset('backend/dist/css/style.min.css') }}" rel="stylesheet">

    <style>
    .password-container {
        position: relative;
        width: 100%;
    }

    .password-container input {
        padding-right: 40px !important;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #a1a1a1;
        z-index: 10;
        font-size: 14px;
    }

    .back-to-beranda {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 20;
    }
    </style>
</head>

<body>

    <div class="main-wrapper">

        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        {{-- Tombol kembali ke tampilan customer --}}
        <a href="{{ route('beranda') }}" class="btn btn-outline-light btn-sm back-to-beranda">
            <i class="fa fa-arrow-left m-r-5"></i> Kembali ke Beranda
        </a>

        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
            <div class="auth-box bg-dark border-top border-secondary">

                {{-- ============================================================== --}}
                {{-- FORMS LOGIN --}}
                {{-- ============================================================== --}}
                <div id="loginform">
                    <div class="logo text-center p-t-20">
                        {{-- BERHASIL DIRAPIKAN: Memanggil file logo asli/besar agar tidak pecah --}}
                        <span class="db">
                            <img src="{{ asset('image/logo.png') }}" alt="logo BSI" style="width: 160px; height: auto;">
                        </span>
                        <h3 class="text-white font-bold m-t-10">BSI CYBER STORE</h3>
                        <p class="text-muted m-b-0">Login khusus admin</p>
                    </div>

                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>{{ session('error') }}</strong>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>{{ session('success') }}</strong>
                    </div>
                    @endif

                    <form class="form-horizontal m-t-20" action="{{ route('backend.login') }}" method="post">
                        @csrf
                        <div class="row p-b-30">
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1">
                                            <i class="ti-user"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="Masukkan Email" aria-label="Username"
                                        aria-describedby="basic-addon1">
                                    @error('email')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-warning text-white" id="basic-addon2">
                                            <i class="ti-pencil"></i>
                                        </span>
                                    </div>
                                    <div class="password-container d-flex flex-grow-1">
                                        <input type="password" name="password" id="password_field"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Masukkan Password">
                                        <i class="fa-solid fa-eye toggle-password" id="eye_icon"
                                            onclick="togglePasswordVisibility()"></i>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 border-top border-secondary">
                                <div class="form-group">
                                    <div class="p-t-20">
                                        <button class="btn btn-info" id="to-recover" type="button">
                                            <i class="fa fa-lock m-r-5"></i> Lost Password?
                                        </button>
                                        <button class="btn btn-success float-right" type="submit">Login</button>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <p class="text-muted">Belum punya akun? <a
                                                href="{{ route('backend.register') }}" class="text-info">Daftar di
                                                sini</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- ============================================================== --}}
                {{-- FORMS RECOVER (LUPA PASSWORD) --}}
                {{-- ============================================================== --}}
                <div id="recoverform" style="display: none;">
                    <div class="text-center">
                        <span class="text-white">Enter your e-mail address below and we will send you instructions how
                            to recover a password.</span>
                    </div>
                    <div class="row m-t-20">
                        <form class="col-12" action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger text-white" id="basic-addon3">
                                        <i class="ti-email"></i>
                                    </span>
                                </div>
                                <input type="email" name="email" class="form-control form-control-lg"
                                    placeholder="Email Address" aria-label="Email" aria-describedby="basic-addon3"
                                    required>
                            </div>
                            <div class="row m-t-10 border-top border-secondary">
                                <div class="col-12 p-t-20">
                                    <a class="btn btn-success" href="#" id="to-login">Back To Login</a>
                                    <button class="btn btn-info float-right" type="submit">Recover</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="{{ asset('backend/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('backend/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();

    $("#to-recover").on("click", function() {
        $("#loginform").hide();
        $("#recoverform").fadeIn();
    });
    $("#to-login").click(function() {
        $("#recoverform").hide();
        $("#loginform").fadeIn();
    });

    function togglePasswordVisibility() {
        const passwordField = document.getElementById("password_field");
        const eyeIcon = document.getElementById("eye_icon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
    </script>

</body>

</html>
