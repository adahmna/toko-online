<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon_univ_bsi.png') }}">
    <title>@yield('judul', 'Karir') - BSI Cyber Store</title>

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Hind:400,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">

    <!-- Custom stylesheet (dipakai untuk class .primary-btn, .input, dll) -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
    body {
        background: #f5f6f8;
        min-height: 100vh;
    }

    .karir-page-wrap {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 10px;
    }

    .karir-topbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 10;
    }

    .karir-topbar img {
        height: 32px;
    }

    .karir-topbar span {
        font-weight: 700;
        color: #333;
    }

    .karir-page-wrap {
        margin-top: 60px;
    }
    </style>
</head>

<body>

    <!-- Topbar minimal, tanpa kategori/menu -->
    <div class="karir-topbar">
        <a href="{{ route('beranda') }}">
            <img src="{{ asset('image/logo.png') }}" alt="BSI Cyber Store">
        </a>
        <span>BSI Cyber Store</span>
    </div>

    <div class="karir-page-wrap">
        <div class="container">
            @yield('content')
        </div>
    </div>

    <!-- jQuery Plugins -->
    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>

</body>

</html>