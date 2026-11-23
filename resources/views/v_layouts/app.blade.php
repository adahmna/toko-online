<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Terapkan tema tersimpan sebelum halaman dirender (anti kedip) -->
    <script>
    (function() {
        var saved = null;
        try {
            saved = localStorage.getItem('theme');
        } catch (e) {}
        var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.setAttribute('data-theme', saved || (prefersDark ? 'dark' : 'light'));
    })();
    </script>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/icon_univ_bsi.png') }}">
    <title>tokoonline</title>

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Hind:400,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">

    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/slick-theme.css') }}">

    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/nouislider.min.css') }}">

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">

    <!-- Custom stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
    /* ===== HEADER STICKY (BARU) ===== */
    #siteHeader {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 1030;
        background: #fff;
        transition: box-shadow 0.2s ease;
    }

    #siteHeader.scrolled {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    }

    [data-theme="dark"] #siteHeader {
        background: #1e1e1e;
    }

    /* ===== HEADER RESPONSIVE (TAMPILAN HP) ===== */
    @media (max-width: 767px) {

        /* --- Bar atas: "Selamat datang" kiri, jam + tanggal kanan, satu baris --- */
        #top-header {
            padding: 0;
        }

        #top-header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            padding: 6px 12px;
        }

        #top-header .container::before,
        #top-header .container::after {
            display: none !important;
        }

        #top-header .pull-left,
        #top-header .pull-right {
            float: none !important;
        }

        #top-header .pull-left {
            flex: 1 1 auto;
            min-width: 0;
            margin-right: 8px;
        }

        #top-header .pull-left span {
            display: block;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #top-header .pull-right {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            white-space: nowrap;
        }

        #top-header .theme-toggle {
            width: 26px;
            height: 26px;
            padding: 0;
            font-size: 13px;
            line-height: 1;
        }

        #tanggalRealtimeCustomer {
            margin-left: 0 !important;
        }

        /* --- Bar utama: logo kiri, ikon (keranjang, notif, akun, menu) kanan --- */
        #header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            padding: 8px 12px;
        }

        #header .container::before,
        #header .container::after {
            display: none !important;
        }

        #header .pull-left,
        #header .pull-right {
            float: none !important;
        }

        #header .header-logo {
            padding: 0;
            margin: 0;
        }

        #header .header-logo .logo img {
            height: 46px;
            width: auto;
            max-height: none;
        }

        #header .header-btns {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        #header .header-btns>li {
            float: none !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Ikon saja, teks label disembunyikan biar muat */
        #header .header-btns>li strong {
            display: none !important;
        }

        #header .header-btns .header-btns-icon {
            float: none !important;
            width: 40px !important;
            height: 40px !important;
            line-height: 38px !important;
            margin: 0 !important;
            font-size: 16px;
            text-align: center;
        }

        #notifBadge {
            top: -8px;
            right: -8px;
        }

        /* Tombol hamburger menu */
        #header .nav-toggle-btn {
            width: 40px;
            height: 40px;
            padding: 0;
            line-height: 38px;
        }

        /* Dropdown jangan keluar layar */
        #header .header-btns .custom-menu {
            right: 0 !important;
            left: auto !important;
            max-width: calc(100vw - 24px);
        }

        .header-notif .custom-menu {
            width: 280px;
        }
    }

    /* HP kecil: rapatkan lagi */
    @media (max-width: 400px) {
        #top-header .pull-left span {
            font-size: 11px;
        }

        #top-header .pull-right {
            font-size: 10px;
            gap: 4px;
        }

        #header .header-btns {
            gap: 6px;
        }

        #header .header-btns .header-btns-icon,
        #header .nav-toggle-btn {
            width: 36px !important;
            height: 36px !important;
            line-height: 34px !important;
        }
    }

    /* ===== PERBAIKAN MENU HAMBURGER (TERTUTUP HEADER STICKY) ===== */
    @media (max-width: 991px) {
        #responsive-nav {
            z-index: 1100 !important;
        }
    }

    /* ===== PERBAIKAN DROPDOWN DI HP: lebar penuh di bawah bar logo, dibuka dengan klik/tap ===== */
    @media (max-width: 767px) {
        #header {
            position: relative !important;
        }

        #header .header-btns>li.dropdown {
            position: static !important;
        }

        #header .header-btns>li.dropdown .custom-menu {
            position: absolute !important;
            top: 100% !important;
            left: 12px !important;
            right: 12px !important;
            width: auto !important;
            min-width: 0 !important;
            max-width: none !important;
            margin: 0 !important;
            transform: none !important;
            max-height: 60vh;
            overflow-y: auto;
            z-index: 1040;
        }

        /* Matikan hover, buka hanya saat diklik/tap */
        #header .header-btns>li.dropdown:hover .custom-menu {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }

        #header .header-btns>li.dropdown.open .custom-menu,
        #header .header-btns>li.dropdown.open:hover .custom-menu {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
    }

    /* ===== KONTEN HALAMAN SELEBAR LAYAR DI HP/TABLET ===== */
    /* Kolom tanpa class col-xs/col-sm (mis. col-md-12) tadinya menyusut mengikuti isinya,
       sehingga keranjang, akun, wishlist, dan bandingkan terlihat sempit/terpotong. */
    @media (max-width: 991px) {
        #store .row>:not([class*="col-xs-"]):not([class*="col-sm-"]):not(.clearfix) {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }
    }

    /* ===== KATEGORI ICON ANIMATION ===== */
    .kategori-icon-anim {
        display: inline-block;
        animation: kategoriWiggle 2.5s ease-in-out infinite;
    }

    @keyframes kategoriWiggle {

        0%,
        100% {
            transform: rotate(0deg);
        }

        10% {
            transform: rotate(-15deg);
        }

        20% {
            transform: rotate(15deg);
        }

        30% {
            transform: rotate(-10deg);
        }

        40% {
            transform: rotate(10deg);
        }

        50% {
            transform: rotate(0deg);
        }
    }

    .category-header:hover .kategori-icon-anim {
        animation: kategoriSpin 0.6s linear infinite;
    }

    @keyframes kategoriSpin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* ===== NOTIFIKASI STYLES ===== */
    .header-notif .header-btns-icon {
        position: relative;
    }

    #notifBadge {
        display: none;
        position: absolute;
        top: -6px;
        right: -10px;
        background: #e74c3c;
        color: #fff;
        font-size: 10px;
        line-height: 1;
        border-radius: 10px;
        padding: 3px 5px;
        font-weight: bold;
    }

    .header-notif .custom-menu {
        width: 320px;
        max-height: 400px;
        overflow-y: auto;
        right: 0;
        left: auto;
    }

    .notif-heading {
        padding: 8px 15px;
        font-weight: bold;
        border-bottom: 1px solid #eee;
        cursor: default;
    }

    .notif-item {
        display: block;
        position: relative;
        padding: 10px 15px 10px 28px;
        border-bottom: 1px solid #f2f2f2;
        color: inherit;
        text-decoration: none;
        transition: background 0.15s;
    }

    a.notif-item:hover {
        background: #f7f9fc;
        color: inherit;
        text-decoration: none;
    }

    .notif-item.notif-unread {
        background: #eaf3ff;
    }

    .notif-item.notif-unread:hover {
        background: #dcebff;
    }

    .notif-item .notif-dot {
        display: none;
        position: absolute;
        left: 12px;
        top: 16px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #1976d2;
    }

    .notif-item.notif-unread .notif-dot {
        display: block;
    }

    .notif-item .notif-title {
        font-weight: 600;
        font-size: 13px;
    }

    .notif-item .notif-sub {
        color: #666;
        font-size: 12px;
    }

    .notif-item .notif-time {
        color: #aaa;
        font-size: 11px;
    }

    .notif-empty,
    .notif-error {
        padding: 15px;
        text-align: center;
        color: #999;
    }

    .notif-error {
        color: #c00;
    }

    .header-btns>li.dropdown {
        position: relative;
    }

    .header-btns>li.dropdown .custom-menu {
        display: none;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease;
    }

    .header-btns>li.dropdown:hover .custom-menu {
        display: block !important;
        opacity: 1;
        visibility: visible;
    }

    /* ===== TOAST SELAMAT DATANG (muncul dari atas) ===== */
    .bsi-toast {
        position: fixed !important;
        top: 20px !important;
        left: 50% !important;
        right: auto !important;
        z-index: 10000;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 12px;
        width: max-content;
        max-width: calc(100vw - 32px);
        box-sizing: border-box;
        padding: 14px 18px;
        background: #fff;
        color: #2b2b2b;
        border-left: 4px solid #F8694A;
        border-radius: 8px;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.2);
        transform: translate(-50%, -160%);
        opacity: 0;
        transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.4s ease;
    }

    .bsi-toast.show {
        transform: translate(-50%, 0);
        opacity: 1;
    }

    .bsi-toast .bsi-toast-icon {
        flex: 0 0 auto;
        font-size: 26px;
        line-height: 1;
    }

    .bsi-toast .bsi-toast-text {
        flex: 1 1 auto;
        font-size: 14px;
        line-height: 1.5;
    }

    .bsi-toast .bsi-toast-close {
        flex: 0 0 auto;
        background: none;
        border: none;
        color: inherit;
        font-size: 20px;
        line-height: 1;
        opacity: 0.6;
        cursor: pointer;
        padding: 0 2px;
    }

    .bsi-toast .bsi-toast-close:hover {
        opacity: 1;
    }

    [data-theme="dark"] .bsi-toast {
        background: #1e1e1e;
        color: #f1f1f1;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.6);
    }
    </style>

    <!-- Dark mode (taruh paling akhir supaya override-nya kepake) -->
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="{{ asset('css/mobile-fix.css') }}?v={{ time() }}">
    <style>
    /* ===== ANIMASI MENU (HP + DESKTOP) ===== */
@keyframes navMasuk {
    from {
        opacity: 0;
        transform: translateX(-26px);
    }

    to {
        opacity: 1;
        transform: none;
    }
}

#responsive-nav.nav-buka .category-header,
#responsive-nav.nav-buka .menu-header,
#responsive-nav.nav-buka .category-list>li,
#responsive-nav.nav-buka .menu-list>li {
    animation: navMasuk .5s cubic-bezier(.22, 1, .36, 1) both;
}

#responsive-nav.nav-buka .category-header { animation-delay: .05s; }
#responsive-nav.nav-buka .menu-header { animation-delay: .12s; }

#responsive-nav.nav-buka .menu-list>li:nth-child(1) { animation-delay: .18s; }
#responsive-nav.nav-buka .menu-list>li:nth-child(2) { animation-delay: .24s; }
#responsive-nav.nav-buka .menu-list>li:nth-child(3) { animation-delay: .30s; }
#responsive-nav.nav-buka .menu-list>li:nth-child(4) { animation-delay: .36s; }
#responsive-nav.nav-buka .menu-list>li:nth-child(5) { animation-delay: .42s; }

#responsive-nav.nav-buka .category-list>li:nth-child(1) { animation-delay: .18s; }
#responsive-nav.nav-buka .category-list>li:nth-child(2) { animation-delay: .24s; }
#responsive-nav.nav-buka .category-list>li:nth-child(3) { animation-delay: .30s; }
#responsive-nav.nav-buka .category-list>li:nth-child(4) { animation-delay: .36s; }
#responsive-nav.nav-buka .category-list>li:nth-child(5) { animation-delay: .42s; }
#responsive-nav.nav-buka .category-list>li:nth-child(6) { animation-delay: .48s; }
/* ===== LOADING HALAMAN MEWAH ===== */
    #pageLoader {
        position: fixed;
        inset: 0;
        z-index: 100000;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 50% 40%, #1f2736 0%, #0b0f17 72%);
        opacity: 1;
        visibility: visible;
        transition: opacity .5s ease, visibility .5s ease;
        animation: plFailsafe 0s linear 8s forwards;
    }

    #pageLoader.pl-hide {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    @keyframes plFailsafe {
        to {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
    }

    .pl-logo {
        position: relative;
        width: 128px;
        height: 128px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pl-logo::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: conic-gradient(from 0deg, transparent 0deg, #F8694A 120deg, #ffd27a 200deg, transparent 300deg);
        -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 4px), #000 calc(100% - 3px));
        mask: radial-gradient(farthest-side, transparent calc(100% - 4px), #000 calc(100% - 3px));
        animation: plSpin 1.4s linear infinite;
    }

    .pl-logo::after {
        content: "";
        position: absolute;
        inset: 10px;
        border-radius: 50%;
        border: 1px solid rgba(255, 210, 122, .25);
        animation: plSpin 3s linear infinite reverse;
    }

    .pl-logo img {
        width: 74px;
        height: auto;
        border-radius: 12px;
        animation: plGlow 1.8s ease-in-out infinite;
    }

    .pl-brand {
        margin-top: 26px;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 6px;
        text-transform: uppercase;
        background: linear-gradient(90deg, #b88a3b 0%, #ffe9a8 40%, #F8694A 60%, #b88a3b 100%);
        background-size: 220% auto;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
        animation: plShine 2.4s linear infinite;
    }

    .pl-sub {
        margin-top: 8px;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: rgba(232, 238, 245, .55);
    }

    .pl-bar {
        position: relative;
        width: 150px;
        height: 3px;
        margin-top: 22px;
        border-radius: 3px;
        overflow: hidden;
        background: rgba(255, 255, 255, .1);
    }

    .pl-bar::after {
        content: "";
        position: absolute;
        top: 0;
        left: -45%;
        width: 45%;
        height: 100%;
        border-radius: 3px;
        background: linear-gradient(90deg, transparent, #F8694A, #ffd27a);
        animation: plBar 1.3s ease-in-out infinite;
    }

    @keyframes plSpin {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes plGlow {

        0%,
        100% {
            filter: drop-shadow(0 0 4px rgba(248, 105, 74, .35));
            transform: scale(.96);
        }

        50% {
            filter: drop-shadow(0 0 16px rgba(255, 210, 122, .8));
            transform: scale(1.04);
        }
    }

    @keyframes plShine {
        to {
            background-position: -220% center;
        }
    }

    @keyframes plBar {
        to {
            left: 100%;
        }
    }
    </style>
</head>

<body data-user="{{ auth()->check() ? (auth()->user()->nama ?? auth()->user()->name ?? '') : '' }}">
    <!-- HEADER (sekarang sticky, lihat id="siteHeader") -->
    <div id="pageLoader">
        <div class="pl-logo"><img src="{{ asset('image/logo.png') }}" alt=""></div>
        <div class="pl-brand">BSI Cyber Store</div>
        <div class="pl-sub">Mohon tunggu sebentar</div>
        <div class="pl-bar"></div>
    </div>
    <header id="siteHeader">
        <!-- top Header -->
        <div id="top-header">
            <div class="container">
                <div class="pull-left">
                    <span>Selamat datang di BSI Cyber Store</span>
                </div>
                <div class="pull-right">
                    <button type="button" id="themeToggle" class="theme-toggle" aria-label="Ganti tema">🌙</button>
                    <span id="jamRealtimeCustomer" style="font-weight:600;"></span>
                    <span id="tanggalRealtimeCustomer" style="margin-left:8px;"></span>
                </div>
            </div>
        </div>
        <!-- /top Header -->

        <!-- header -->
        <div id="header">
            <div class="container">
                <div class="pull-left">
                    <!-- Logo -->
                    <div class="header-logo">
                        <a class="logo" href="#">
                            <img src="{{ asset('image/logo.png') }}" alt="">
                        </a>
                    </div>
                    <!-- /Logo -->
                </div>
                <div class="pull-right">
                    <ul class="header-btns">
                        <!-- Cart -->
                        <li class="header-cart dropdown default-dropdown">
                            <a href="{{ route('order.cart') }}">
                                <div class="header-btns-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <strong class="text-uppercase">Keranjang</strong>
                            </a>
                        </li>
                        <!-- /Cart -->

                        @auth
                        <!-- Notifikasi -->
                        <li class="header-notif dropdown default-dropdown">
                            <div class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="true">
                                <div class="header-btns-icon">
                                    <i class="fa fa-bell-o"></i>
                                    <span id="notifBadge">0</span>
                                </div>
                                <strong class="text-uppercase">Notifikasi <i class="fa fa-caret-down"></i></strong>
                            </div>
                            <ul class="custom-menu">
                                <li class="notif-heading">Notifikasi Terbaru</li>
                                <li id="notifList">
                                    <div class="notif-empty">Memuat...</div>
                                </li>
                            </ul>
                        </li>
                        <!-- /Notifikasi -->
                        @endauth

                        <!-- Account -->
                        <li class="header-account dropdown default-dropdown">
                            @if (Auth::check())
                            <!-- Tampilan Setelah Login -->
                            <div class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="true">
                                <div class="header-btns-icon">
                                    <i class="fa fa-user-o"></i>
                                </div>
                                <strong class="text-uppercase">{{ Auth::user()->nama }} <i
                                        class="fa fa-caret-down"></i></strong>
                            </div>
                            <ul class="custom-menu">
                                <li><a href="{{ route('customer.akun', ['id' => Auth::user()->id]) }}"><i
                                            class="fa fa-user-o"></i> Akun Saya</a></li>
                                <li><a href="{{ route('customer.riwayat.index') }}"><i class="fa fa-check"></i>
                                        Riwayat</a></li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-power-off"></i> Keluar
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                            @else
                            <!-- Tampilan Sebelum Login -->
                            <div class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="true">
                                <div class="header-btns-icon">
                                    <i class="fa fa-user-o"></i>
                                </div>
                                <strong class="text-uppercase">Akun Saya <i class="fa fa-caret-down"></i></strong>
                            </div>
                            <ul class="custom-menu">
                                <li><a href="{{ route('customer.login') }}"><i class="fa fa-user"></i> Login sebagai
                                        Customer</a></li>
                                <li><a href="{{ route('backend.login') }}"><i class="fa fa-shield"></i> Login sebagai
                                        Admin</a></li>
                            </ul>
                            @endif
                        </li>
                        <!-- /Account -->

                        <!-- Mobile nav toggle-->
                        <li class="nav-toggle">
                            <button class="nav-toggle-btn main-btn icon-btn"><i class="fa fa-bars"></i></button>
                        </li>
                        <!-- / Mobile nav toggle -->
                    </ul>
                </div>
            </div>
            <!-- /header -->
        </div>
    </header>
    <!-- /HEADER -->

    <!-- NAVIGATION -->
    <div id="navigation">
        <div class="container">
            <div id="responsive-nav">
                <!-- category nav -->
                <div class="category-nav">
                    <span class="category-header">Kategori <i class="fa fa-list kategori-icon-anim"></i></span>
                    <ul class="category-list">
                        @foreach ($kategoris as $kategori)
                        <li><a href="{{ route('produk.kategori', $kategori->id) }}">{{ $kategori->nama_kategori }}</a>
                        </li>
                        @endforeach
                        <li><a href="{{ route('customer.produk.index') }}">Lihat Semua</a></li>
                    </ul>
                </div>
                <!-- /category nav -->

                <!-- menu nav -->
                <div class="menu-nav">
                    <span class="menu-header">Menu <i class="fa fa-bars"></i></span>
                    <ul class="menu-list">
                        <li><a href="{{ route('beranda') }}">Beranda</a></li>
                        <li><a href="{{ route('customer.produk.index') }}">Produk</a></li>
                        <li><a href="https://maps.app.goo.gl/mszkz9TNzRnMFh9M7" target="_blank"
                                rel="noopener">Lokasi</a></li>
                        <li><a href="{{ route('kontak') }}">Hubungi Kami</a></li>
                        <li><a href="{{ route('karir.index') }}">Karir</a></li>
                    </ul>
                </div>
                <!-- /menu nav -->
            </div>
        </div>
    </div>
    <!-- /NAVIGATION -->

    <!-- HOME -->
    @if (request()->segment(1) == '' || request()->segment(1) == 'beranda')
    <div id="home">
        <div class="container">
            <div class="home-wrap">
                <div id="home-slick">
                    <!-- banner -->
                    <div class="banner banner-1">
                        <img src="{{ asset('frontend/img/banner01.jpg') }}" alt="">
                        <div class="banner-caption text-center">
                            <h1>Diskon Tas</h1>
                            <h3 class="white-color font-weak">Diskon hingga 50%</h3>
                            <a href="{{ $produkBanner1 ? route('produk.detail', $produkBanner1->id) : '#' }}"
                                class="primary-btn">Belanja Sekarang</a>
                        </div>
                    </div>
                    <!-- /banner -->

                    <!-- banner -->
                    <div class="banner banner-1">
                        <img src="{{ asset('frontend/img/banner02.jpg') }}" alt="">
                        <div class="banner-caption">
                            <h1 class="primary-color">PROMO PANAS<br><span class="white-color font-weak">Diskon hingga
                                    50%</span></h1>
                            <a href="{{ $produkBanner2 ? route('produk.detail', $produkBanner2->id) : '#' }}"
                                class="primary-btn">Belanja Sekarang</a>
                        </div>
                    </div>
                    <!-- /banner -->

                    <!-- banner -->
                    <div class="banner banner-1">
                        <img src="{{ asset('frontend/img/banner03.jpg') }}" alt="">
                        <div class="banner-caption">
                            <h1 class="white-color">Koleksi <span>Produk Baru</span></h1>
                            <a href="{{ $produkBanner3 ? route('produk.detail', $produkBanner3->id) : '#' }}"
                                class="primary-btn">Belanja Sekarang</a>
                        </div>
                    </div>
                    <!-- /banner -->
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- /HOME -->

    <!-- section -->
    <div class="section">
        <div class="container">
            <div class="row">
                <!-- ASIDE -->
                <div id="aside"
                    class="col-md-3 {{ request()->segment(1) == '' || request()->segment(1) == 'beranda' ? '' : 'hidden-xs hidden-sm' }}">
                    <!-- aside widget -->
                    {{-- terlaris-only-home --}}
                    @if (request()->segment(1) == '' || request()->segment(1) == 'beranda')
                    <div class="aside">
                        <h3 class="aside-title">Produk Terlaris</h3>

                        @php
                        // 3 produk aktif dengan jumlah transaksi terbanyak
                        $produkTerlaris = \App\Models\Produk::withCount('pesananDetail')
                        ->where('status', 1)
                        ->orderByDesc('pesanan_detail_count')
                        ->take(3)
                        ->get();
                        @endphp

                        @forelse ($produkTerlaris as $pt)
                        <!-- widget product -->
                        <div class="product product-widget">
                            <div class="product-thumb">
                                <img src="{{ asset('storage/img-produk/' . $pt->foto) }}" alt="{{ $pt->nama_produk }}">
                            </div>
                            <div class="product-body">
                                <h2 class="product-name">
                                    <a href="{{ route('produk.detail', $pt->id) }}">{{ $pt->nama_produk }}</a>
                                </h2>
                                <h3 class="product-price">Rp. {{ number_format($pt->harga, 0, ',', '.') }}</h3>
                                <small class="text-muted">{{ $pt->pesanan_detail_count }}x dipesan</small>
                            </div>
                        </div>
                        <!-- /widget product -->
                        @empty
                        <p class="text-muted">Belum ada data penjualan.</p>
                        @endforelse
                    </div>
                    <!-- /aside widget -->
                    @endif

                    <!-- aside widget -->
                    <div class="aside">
                        <h3 class="aside-title">Filter Kategori</h3>
                        <ul class="list-links">
                            @php
                            $kategoriSidebar = DB::table('kategori')->orderBy('nama_kategori', 'asc')->get();
                            @endphp
                            @foreach ($kategoriSidebar as $row)
                            <li><a href="{{ route('produk.kategori', data_get($row, 'id')) }}">{{ data_get($row, 'nama_kategori') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /aside widget -->
                </div>
                <!-- /ASIDE -->

                <!-- MAIN -->
                <div id="main" class="col-md-9">
                    <!-- STORE -->
                    <div id="store">
                        <div class="row">
                            @yield('content')
                            <div class="clearfix visible-md visible-lg visible-sm visible-xs"></div>
                        </div>
                    </div>
                    <!-- /STORE -->
                </div>
                <!-- /MAIN -->
            </div>
        </div>
    </div>
    <!-- /section -->

    <!-- FOOTER -->
    <footer id="footer" class="section section-grey">
        <div class="container">
            <div class="row">
                <!-- footer widget -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="footer">
                        <!-- footer logo -->
                        <div class="footer-logo">
                            <a class="logo" href="#">
                                <img src="{{ asset('frontend/img/logo.png') }}" alt="">
                            </a>
                        </div>
                        <!-- /footer logo -->

                        <p>BSI Cyber Store adalah platform belanja online resmi Universitas BSI, menyediakan berbagai
                            produk kebutuhan mahasiswa dan masyarakat umum dengan harga terjangkau dan pengiriman cepat.
                        </p>

                        <!-- footer social -->
                        <ul class="footer-social">
                            <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fab fa-google-plus"></i></a></li>
                            <li><a href="#"><i class="fab fa-pinterest"></i></a></li>
                        </ul>
                        <!-- /footer social -->
                    </div>
                </div>
                <!-- /footer widget -->

                <!-- footer widget -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">Akun Saya</h3>
                        <ul class="list-links">
                            <li><a
                                    href="{{ auth()->check() ? route('customer.akun', ['id' => auth()->id()]) : route('customer.login') }}">Akun
                                    Saya</a></li>
                            <li><a href="{{ route('wishlist') }}">Wishlist Saya</a></li>
                            <li><a href="{{ route('bandingkan') }}">Bandingkan</a></li>
                            <li><a href="{{ route('order.cart') }}">Checkout</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /footer widget -->

                <div class="clearfix visible-sm visible-xs"></div>

                <!-- footer widget -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">Layanan Pelanggan</h3>
                        <ul class="list-links">
                            <li><a href="{{ route('tentang') }}">Tentang Kami</a></li>
                            <li><a href="{{ route('pengiriman') }}">Pengiriman & Pengembalian</a></li>
                            <li><a href="{{ route('panduan-pengiriman') }}">Panduan Pengiriman</a></li>
                            <li><a href="{{ route('kontak') }}">Tanya Jawab</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /footer widget -->

                <!-- footer subscribe -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="footer">
                        <h3 class="footer-header">Tetap Terhubung</h3>
                        <p>Belanja mudah, aman, dan terpercaya bersama BSI Cyber Store.</p>

                        @if (session('newsletter_success'))
                        <div class="alert alert-success" style="padding:8px;margin-bottom:10px;font-size:13px;">
                            {{ session('newsletter_success') }}
                        </div>
                        @endif
                        @error('email')
                        <div class="alert alert-danger" style="padding:8px;margin-bottom:10px;font-size:13px;">
                            {{ $message }}
                        </div>
                        @enderror

                        <form action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="email" name="email" class="input" placeholder="Masukkan Alamat Email"
                                    required value="{{ old('email') }}">
                            </div>
                            <button type="submit" class="primary-btn">Berlangganan Newsletter</button>
                        </form>
                    </div>
                </div>
                <!-- /footer subscribe -->
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <!-- footer copyright -->
                    <div class="footer-copyright">
                        &copy;<script>
                        document.write(new Date().getFullYear());
                        </script> BSI Cyber Store. All rights reserved
                    </div>
                    <!-- /footer copyright -->
                </div>
            </div>
        </div>
    </footer>
    <!-- /FOOTER -->

    <!-- jQuery Plugins -->
    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>

    <!-- Toggle dark/light mode -->
    <script src="{{ asset('js/theme-toggle.js') }}"></script>

    <!-- ===== LIVE CHAT CUSTOMER-ADMIN ===== -->
    @include('frontend.partials.livechat-widget')

    <!-- ===== BAYANGAN HEADER SAAT SCROLL (BARU) ===== -->
    <script>
    (function() {
        const header = document.getElementById('siteHeader');
        if (!header) return;
        const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 10);
        window.addEventListener('scroll', onScroll, {
            passive: true
        });
        onScroll();
    })();
    </script>
    <!-- ===== END BAYANGAN HEADER ===== -->

    <!-- ===== JAM & TANGGAL REAL-TIME (CUSTOMER) ===== -->
    <script>
    (function() {
        const jamEl = document.getElementById('jamRealtimeCustomer');
        const tglEl = document.getElementById('tanggalRealtimeCustomer');
        if (!jamEl || !tglEl) return;

        const hariList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
        const bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember'
        ];

        function updateJam() {
            const now = new Date();
            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');
            jamEl.textContent = `${jam}:${menit}:${detik}`;
            tglEl.textContent =
                `${hariList[now.getDay()]}, ${now.getDate()} ${bulanList[now.getMonth()]} ${now.getFullYear()}`;
        }

        updateJam();
        setInterval(updateJam, 1000);
    })();
    </script>
    <!-- ===== END JAM REAL-TIME ===== -->

    @auth
    <!-- ===== NOTIFIKASI REAL-TIME ===== -->
    <script>
    (function() {
        const badge = document.getElementById('notifBadge');
        const list = document.getElementById('notifList');
        if (!badge || !list) return;

        const STORAGE_KEY = 'notifSeenIds_{{ auth()->id() }}';

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        function getSeenIds() {
            try {
                return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            } catch (e) {
                return [];
            }
        }

        function saveSeenIds(ids) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
        }

        function loadNotif(markAsRead = false) {
            fetch("{{ route('backend.notifikasi') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const orders = data.orders || [];
                    const users = data.users || [];

                    const orderTags = orders.map(o => 'order-' + o.id + '-' + o.status);
                    const userTags = users.map(u => 'user-' + u.email + '-' + u.waktu);
                    const allTags = [...orderTags, ...userTags];

                    const seenBefore = getSeenIds();
                    const unseenCount = allTags.filter(tag => !seenBefore.includes(tag)).length;

                    if (unseenCount > 0) {
                        badge.style.display = 'inline-block';
                        badge.textContent = unseenCount;
                    } else {
                        badge.style.display = 'none';
                    }

                    let html = '';

                    orders.forEach((o, idx) => {
                        const href = o.link ? escapeHtml(o.link) : '#';
                        const isUnread = !seenBefore.includes(orderTags[idx]);
                        html += `
                                    <li>
                                        <a href="${href}" class="notif-item ${isUnread ? 'notif-unread' : ''}">
                                            <span class="notif-dot"></span>
                                            <div class="notif-title">📦 ${escapeHtml(o.kode_pesanan)} &middot; ${escapeHtml(o.status)}</div>
                                            <div class="notif-sub">${escapeHtml(o.customer_name)} &bull; ${escapeHtml(o.total_format)}</div>
                                            <div class="notif-time">${escapeHtml(o.waktu)}</div>
                                        </a>
                                    </li>`;
                    });

                    users.forEach((u, idx) => {
                        const isUnread = !seenBefore.includes(userTags[idx]);
                        html += `
                                    <li class="notif-item ${isUnread ? 'notif-unread' : ''}">
                                        <span class="notif-dot"></span>
                                        <div class="notif-title">👤 ${escapeHtml(u.nama)} (${escapeHtml(u.role)})</div>
                                        <div class="notif-time">${escapeHtml(u.waktu)}</div>
                                    </li>`;
                    });

                    list.innerHTML = html || '<div class="notif-empty">Tidak ada notifikasi baru</div>';

                    if (markAsRead) {
                        saveSeenIds(allTags);
                    }
                })
                .catch(() => {
                    list.innerHTML = '<div class="notif-error">Gagal memuat notifikasi</div>';
                });
        }

        document.querySelector('.header-notif .dropdown-toggle')?.addEventListener('click', () => loadNotif(true));

        loadNotif(false);
        setInterval(() => loadNotif(false), 30000);
    })();
    </script>
    <!-- ===== END NOTIFIKASI ===== -->
    @endauth

    @auth
    @if (session('welcome_shown') !== Auth::id())
    @php session(['welcome_shown' => Auth::id()]); @endphp
    <!-- ===== TOAST SELAMAT DATANG ===== -->
    <div id="bsiToast" class="bsi-toast" role="alert">
        <div class="bsi-toast-icon">👋</div>
        <div class="bsi-toast-text">
            Halo, selamat datang <strong>{{ Auth::user()->nama }}</strong> di BSI Cyber Store
        </div>
        <button type="button" class="bsi-toast-close" id="bsiToastClose" aria-label="Tutup">&times;</button>
    </div>
    <script>
    (function() {
        const toast = document.getElementById('bsiToast');
        const closeBtn = document.getElementById('bsiToastClose');
        if (!toast) return;

        function hide() {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }

        requestAnimationFrame(() => setTimeout(() => toast.classList.add('show'), 100));
        const timer = setTimeout(hide, 5000);

        closeBtn.addEventListener('click', function() {
            clearTimeout(timer);
            hide();
        });
    })();
    </script>
    <!-- ===== END TOAST SELAMAT DATANG ===== -->
    @endif
    @endauth

    <!-- ===== IKON HAMBURGER JADI X (nav-icon-final) ===== -->
    <style>
    .nav-toggle-btn i {
        display: inline-block;
        transition: transform .3s ease;
    }

    .nav-toggle-btn i.fa-times {
        transform: rotate(90deg);
    }

    /* animasi ikon panel diaktifkan */
    </style>
    <script>
    (function() {
        var btn = document.querySelector('.nav-toggle-btn');
        if (!btn) return;
        var ico = btn.querySelector('i');
        if (!ico) return;

        function benarBenarTampil(el) {
            for (var n = el; n && n.nodeType === 1; n = n.parentElement) {
                var cs = getComputedStyle(n);
                if (cs.display === 'none' || cs.visibility === 'hidden' || parseFloat(cs.opacity) < 0.05)
                return false;
            }
            var r = el.getBoundingClientRect();
            if (r.width < 5 || r.height < 5) return false;
            var x = r.left + r.width / 2,
                y = r.top + r.height / 2;
            if (x < 0 || y < 0 || x > window.innerWidth || y > window.innerHeight) return false;
            var top = document.elementFromPoint(x, y);
            return !!top && (el === top || el.contains(top));
        }

        function menuTerbuka() {
            if (window.innerWidth > 991) return false;
            var h = document.querySelector('.menu-header');
            return !!h && benarBenarTampil(h);
        }

        function sinkron() {
            var buka = menuTerbuka();
            ico.classList.toggle('fa-times', buka);
            ico.classList.toggle('fa-bars', !buka);
        }

        document.addEventListener('click', function() {
            setTimeout(sinkron, 50);
            setTimeout(sinkron, 450);
        }, true);

        new MutationObserver(sinkron)
            .observe(document.body, {
                attributes: true,
                subtree: true,
                attributeFilter: ['class', 'style']
            });

        window.addEventListener('resize', sinkron);
        sinkron();
    })();
    </script>
    <!-- ===== END IKON HAMBURGER JADI X ===== -->

    <!-- ===== MENU HEADER: ikon sama dengan KATEGORI, tanpa animasi ===== -->
    <style>
    /* animasi ikon panel diaktifkan */
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var menuIco = document.querySelector('.menu-header i');
        if (!menuIco) return;

        // cari ikon di judul KATEGORI (di panel, bukan label di kartu produk)
        var ikonKategori = null;
        var w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        var n;
        while ((n = w.nextNode())) {
            if (n.nodeValue.trim().toUpperCase() !== 'KATEGORI') continue;
            var el = n.parentElement;
            for (var i = 0; i < 3 && el && el !== document.body; i++) {
                var ico = el.querySelector('i[class*="fa"]');
                if (ico && !ico.closest('.menu-header')) {
                    ikonKategori = ico;
                    break;
                }
                el = el.parentElement;
            }
            if (ikonKategori) break;
        }

        // kalau tidak ketemu, pakai ikon daftar bawaan Font Awesome
        var kelas = ikonKategori ? ikonKategori.className : 'fa fa-list-ul';
        menuIco.className = kelas;
    });
    </script>
    <!-- ===== END MENU HEADER STATIS ===== -->

    <!-- ===== BAR ATAS: teks selamat datang jangan terpotong ===== -->
    <style>
    @media (max-width: 768px) {

        .topbar-fix.container,
        .topbar-fix.container-fluid,
        .topbar-fix .container,
        .topbar-fix .container-fluid {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .topbar-fix,
        .topbar-fix * {
            text-overflow: clip !important;
        }

        .topbar-fix-teks {
            white-space: normal !important;
            overflow: visible !important;
            font-size: 11px !important;
            line-height: 1.3 !important;
            min-width: 0;
        }
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        var n;
        while ((n = w.nextNode())) {
            if (n.nodeValue.indexOf('Selamat datang di') === -1) continue;
            var el = n.parentElement;
            el.classList.add('topbar-fix-teks');
            for (var i = 0; i < 5 && el && el !== document.body; i++) {
                if (el.textContent.length > 300) break;
                el.classList.add('topbar-fix');
                el = el.parentElement;
            }
            break;
        }
    });
    </script>
    <!-- ===== END BAR ATAS ===== -->

    <!-- ===== KARTU PRODUK: tombol berdampingan + label kecil ===== -->
    <style>
    @media (max-width: 768px) {
        .aksi-produk-fix {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap;
            align-items: center !important;
            gap: 10px !important;
        }

        .aksi-produk-fix>* {
            margin: 0 !important;
            float: none !important;
        }

        .lbl-kecil,
        .lbl-kecil * {
            font-size: 10px !important;
            line-height: 1.2 !important;
        }

        .lbl-kecil,
        .lbl-kecil>* {
            padding: 4px 8px !important;
        }
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function adalahPesan(b) {
            return b.textContent.trim().toUpperCase() === 'PESAN';
        }
        var semuaPesan = Array.prototype.filter.call(document.querySelectorAll('a, button'), adalahPesan);

        // 1. tombol pencarian + PESAN berdampingan
        semuaPesan.forEach(function(b) {
            var el = b;
            for (var i = 0; i < 4 && el.parentElement; i++) {
                el = el.parentElement;
                if (el.querySelector('.fa-search, .fa-magnifying-glass, [class*="search"]')) {
                    el.classList.add('aksi-produk-fix');
                    break;
                }
            }
        });

        // kartu produk = ancestor yang berisi tepat satu tombol PESAN
        function dalamKartuProduk(el) {
            for (var p = el.parentElement; p && p !== document.body; p = p.parentElement) {
                var jumlah = Array.prototype.filter.call(p.querySelectorAll('a, button'), adalahPesan).length;
                if (jumlah === 1) return true;
                if (jumlah > 1) return false;
            }
            return false;
        }

        // 2. label Kategori: kecil hanya di kartu produk
        var daftar = [];
        var w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        var n;
        while ((n = w.nextNode())) {
            if (n.nodeValue.trim().toLowerCase() === 'kategori') daftar.push(n.parentElement);
        }

        var menuHeader = document.querySelector('.menu-header');
        daftar.forEach(function(el) {
            if (dalamKartuProduk(el)) {
                var p = el.parentElement;
                if (p && p.textContent.trim().length < 40) {
                    p.classList.add('lbl-kecil');
                } else {
                    el.classList.add('lbl-kecil');
                    if (el.nextElementSibling) el.nextElementSibling.classList.add('lbl-kecil');
                }
            } else if (menuHeader) {
                // judul KATEGORI di panel: samakan dengan MENU
                var cs = getComputedStyle(menuHeader);
                var target = el;
                target.style.setProperty('font-size', cs.fontSize, 'important');
                target.style.setProperty('font-weight', cs.fontWeight, 'important');
                target.style.setProperty('letter-spacing', cs.letterSpacing, 'important');
                target.style.setProperty('line-height', cs.lineHeight, 'important');
                var box = el.parentElement;
                if (box && box.textContent.trim().length < 40) {
                    box.style.setProperty('padding-top', cs.paddingTop, 'important');
                    box.style.setProperty('padding-bottom', cs.paddingBottom, 'important');
                }
            }
        });
    });
    </script>
    <!-- ===== END KARTU PRODUK ===== -->

    <!-- ===== NAMA USER DI ATAS IKON HEADER (MOBILE) ===== -->
    <style>
    .user-label-mobile {
        display: none !important;
    }

    @media (max-width: 991px) {
        #header .header-btns.user-label-wrap {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center;
            justify-content: flex-end;
        }

        .user-label-mobile {
            display: block !important;
            order: -1;
            /* width:0 + min-width:100% = isi satu baris penuh tanpa melebarkan header */
            flex: 0 0 100% !important;
            width: 0 !important;
            min-width: 100% !important;
            text-align: right;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: .02em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0 0 4px 0 !important;
            padding: 0 !important;
            list-style: none;
            float: none !important;
        }

        [data-theme="dark"] .user-label-mobile {
            color: #f1f1f1;
        }
    }

    @media (max-width: 480px) {
        .user-label-mobile {
            font-size: 9.5px;
        }
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var nama = document.body.dataset.user || '';
        if (!nama) return; // tamu (belum login): tidak ada label

        var wadah = document.querySelector('#header .header-btns');
        if (!wadah || wadah.querySelector('.user-label-mobile')) return;

        var lbl = document.createElement('div');
        lbl.className = 'user-label-mobile';
        lbl.textContent = nama;
        wadah.insertBefore(lbl, wadah.firstChild);
        wadah.classList.add('user-label-wrap');
    });
    </script>
    <!-- ===== END NAMA USER DI ATAS IKON HEADER (MOBILE) ===== -->

    <!-- ===== CHAT: kotak pesan lebih besar + teks di tengah ===== -->
    <style>
    .chat-input-fix {
        height: 44px !important;
        min-height: 44px !important;
        max-height: 120px;
        padding: 0 14px !important;
        line-height: 44px !important;
        font-size: 14px !important;
        box-sizing: border-box !important;
        resize: none !important;
        flex: 1 1 0 !important;
        min-width: 0 !important;
        width: auto !important;
        max-width: none !important;
    }

    .chat-input-fix::placeholder {
        line-height: 44px;
    }

    .chat-input-fix.sedang-ngetik {
        line-height: 1.4 !important;
        padding: 11px 14px !important;
    }

    .chat-ikon-kecil {
        width: 26px !important;
        min-width: 26px !important;
        height: 26px !important;
        padding: 0 !important;
        margin: 0 2px !important;
        flex: 0 0 26px !important;
    }

    .chat-ikon-kecil img,
    .chat-ikon-kecil svg,
    .chat-ikon-kecil canvas {
        width: 20px !important;
        height: 20px !important;
        max-width: 20px !important;
        max-height: 20px !important;
    }

    .chat-ikon-kecil i {
        font-size: 16px !important;
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var kotak = document.querySelector('textarea[placeholder*="Ketik" i], input[placeholder*="Ketik" i]');
        if (!kotak) {
            console.warn('chat: kotak pesan tidak ketemu');
            return;
        }

        kotak.classList.add('chat-input-fix');

        // hanya ikon kecil di sebelah kiri kotak (klip, tas, emoji); Kirim dan mic tidak disentuh
        var wadah = kotak.parentElement;
        Array.prototype.forEach.call(wadah.children, function(c) {
            if (c === kotak || c.contains(kotak)) return;
            // hanya elemen sebelum kotak
            if (!(c.compareDocumentPosition(kotak) & Node.DOCUMENT_POSITION_FOLLOWING)) return;
            var info = (c.className + ' ' + c.id + ' ' + c.textContent).toLowerCase();
            if (/kirim|send|mic|voice|record/.test(info)) return;
            c.classList.add('chat-ikon-kecil');
        });

        function sesuaikan() {
            var adaIsi = kotak.value.length > 0;
            kotak.classList.toggle('sedang-ngetik', adaIsi && kotak.tagName === 'TEXTAREA');
            if (kotak.tagName === 'TEXTAREA' && adaIsi) {
                kotak.style.height = 'auto';
                kotak.style.height = Math.min(kotak.scrollHeight, 120) + 'px';
            } else {
                kotak.style.height = '';
            }
        }
        kotak.addEventListener('input', sesuaikan);
        sesuaikan();
    });
    </script>
    <!-- ===== END CHAT ===== -->
    <script>
    (function() {
        /* --- animasi menu (HP + desktop) --- */
        var navBtn = document.querySelector('.nav-toggle-btn');
        var nav = document.getElementById('responsive-nav');
        if (nav) {
            // desktop: animasi jalan otomatis saat halaman dimuat
            nav.classList.add('nav-buka');
        }
        if (navBtn && nav) {
            // HP: ulangi animasi tiap menu dibuka
            navBtn.addEventListener('click', function() {
                nav.classList.remove('nav-buka');
                void nav.offsetWidth;
                nav.classList.add('nav-buka');
            });
        }

        /* --- loading halaman --- */
        var loader = document.getElementById('pageLoader');
        if (!loader) return;
        var timer;

        function sembunyi() {
            clearTimeout(timer);
            loader.classList.add('pl-hide');
        }

        function tampil() {
            loader.style.animation = 'none';
            loader.classList.remove('pl-hide');
            clearTimeout(timer);
            timer = setTimeout(sembunyi, 15000);
        }
        if (document.readyState === 'complete') {
            setTimeout(sembunyi, 250);
        } else {
            window.addEventListener('load', function() {
                setTimeout(sembunyi, 250);
            });
        }
        window.addEventListener('pageshow', function(e) {
            if (e.persisted) sembunyi();
        });

        document.addEventListener('click', function(e) {
            var a = e.target.closest ? e.target.closest('a') : null;
            if (!a || e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0) return;
            var href = a.getAttribute('href') || '';
            if (!href || href.charAt(0) === '#' || /^(javascript:|mailto:|tel:)/i.test(href)) return;
            if (a.target === '_blank' || a.hasAttribute('download') || a.hasAttribute('data-toggle'))
        return;
            if (a.origin && a.origin !== location.origin) return;
            tampil();
        });
        document.addEventListener('submit', function(e) {
            if (!e.defaultPrevented) tampil();
        });
    })();
    </script>
    <script>
    /* tutup-chat-luar */
    (function() {
        function cariPanel() {
            var input = document.querySelector('textarea[placeholder*="Ketik" i], input[placeholder*="Ketik" i]');
            if (!input) return null;
            for (var el = input.parentElement; el && el !== document.body; el = el.parentElement) {
                var cs = getComputedStyle(el),
                    r = el.getBoundingClientRect();
                if ((cs.position === 'fixed' || cs.position === 'absolute') && r.width > 200 && r.height > 200)
                    return el;
            }
            return null;
        }

        function tampil(el) {
            var cs = getComputedStyle(el),
                r = el.getBoundingClientRect();
            return cs.display !== 'none' && cs.visibility !== 'hidden' && parseFloat(cs.opacity) > 0.05 && r.width >
                0 && r.height > 0;
        }

        function dalamFixedLain(target, panel) {
            for (var el = target; el && el !== document.body; el = el.parentElement) {
                if (el === panel) return false;
                if (getComputedStyle(el).position === 'fixed') return true;
            }
            return false;
        }
        document.addEventListener('click', function(e) {
            var panel = cariPanel();
            if (!panel || !tampil(panel)) return;
            if (panel.contains(e.target)) return;
            if (dalamFixedLain(e.target, panel)) return;
            var tutup = panel.querySelector(
                '[class*="close" i], [id*="close" i], [aria-label*="close" i], [aria-label*="tutup" i], [onclick*="close" i], [onclick*="toggle" i]'
                );
            if (tutup) tutup.click();
        }, true);
    })();
    </script>
    <!-- ===== FOOTER RESPONSIVE (HP) ===== -->
    <style>
    @media (max-width: 767px) {
        #footer {
            padding-top: 28px;
            padding-bottom: 16px;
        }

        #footer .footer {
            margin-bottom: 22px;
        }

        /* Brand: logo, deskripsi, sosmed rata tengah */
        #footer .footer-logo {
            text-align: center;
            margin-bottom: 12px;
        }

        #footer .footer-logo img {
            height: 46px;
            width: auto;
            max-width: 100%;
        }

        #footer .footer > p {
            text-align: center;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 0;
        }

        #footer .footer-social {
            display: flex !important;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin: 14px 0 0 !important;
            padding: 0 !important;
            list-style: none;
        }

        #footer .footer-social li {
            float: none !important;
            margin: 0 !important;
        }

        /* Dua kolom link berdampingan */
        #footer .footer-header {
            font-size: 14px;
            margin: 0 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(128, 128, 128, 0.25);
        }

        #footer .list-links li {
            margin-bottom: 8px;
            font-size: 13px;
            line-height: 1.4;
        }

        #footer .list-links li a {
            display: block;
            padding: 2px 0;
        }

        /* Newsletter selebar layar */
        #footer .footer .form-group {
            margin-bottom: 10px;
        }

        #footer .footer input.input,
        #footer .footer input[type="email"] {
            width: 100%;
            box-sizing: border-box;
        }

        #footer .footer .primary-btn {
            display: block;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        #footer .footer .alert {
            text-align: left;
        }

        /* Copyright */
        #footer hr {
            margin: 6px 0 14px;
        }

        #footer .footer-copyright {
            font-size: 12px;
            line-height: 1.5;
            text-align: center;
        }

        #footer .col-md-offset-2 {
            margin-left: 0;
        }
    }

    @media (max-width: 380px) {
        #footer .footer-header {
            font-size: 13px;
        }

        #footer .list-links li {
            font-size: 12px;
        }
    }
    </style>
    <!-- ===== END FOOTER RESPONSIVE ===== -->
    <!-- ===== ASIDE RESPONSIVE (HP) ===== -->
    <style>
    @media (max-width: 991px) {
        #aside {
            margin-bottom: 8px;
        }

        #aside .aside {
            margin-bottom: 26px;
        }

        /* Judul + garis bawah selebar kolom */
        #aside .aside-title {
            font-size: 16px;
            margin: 0 0 16px;
            padding-bottom: 10px;
            position: relative;
            border-bottom: 1px solid rgba(128, 128, 128, 0.25);
        }

        #aside .aside-title::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -1px;
            width: 60px;
            height: 2px;
            background: #F8694A;
        }

        /* ---- Produk Terlaris: kartu horizontal ---- */
        #aside .product-widget {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            gap: 14px;
            position: relative !important;
            padding: 10px !important;
            margin: 0 0 10px !important;
            min-height: 0 !important;
            height: auto !important;
            border: 1px solid rgba(128, 128, 128, 0.25);
            border-radius: 8px;
            float: none !important;
            width: 100% !important;
            box-sizing: border-box;
        }

        #aside .product-widget::before,
        #aside .product-widget::after {
            display: none !important;
            content: none !important;
        }

        #aside .product-widget .product-thumb {
            position: static !important;
            top: auto !important;
            left: auto !important;
            flex: 0 0 72px !important;
            width: 72px !important;
            height: 72px !important;
            margin: 0 !important;
            padding: 0 !important;
            float: none !important;
            border-radius: 6px;
            overflow: hidden;
        }

        #aside .product-widget .product-thumb img {
            position: static !important;
            display: block;
            width: 100% !important;
            height: 100% !important;
            max-width: none !important;
            object-fit: cover;
        }

        #aside .product-widget .product-body {
            position: static !important;
            flex: 1 1 auto;
            min-width: 0;
            margin: 0 !important;
            padding: 0 !important;
            float: none !important;
            text-align: left !important;
        }

        #aside .product-widget .product-name {
            font-size: 14px;
            line-height: 1.3;
            margin: 0 0 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        #aside .product-widget .product-price {
            font-size: 15px;
            margin: 0 0 2px;
        }

        #aside .product-widget small {
            display: block;
            font-size: 11px;
        }
        /* ---- Filter Kategori: chip ---- */
        #aside .list-links {
            display: flex !important;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none;
        }

        #aside .list-links li {
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #aside .list-links li a {
            display: inline-block;
            padding: 7px 14px;
            font-size: 13px;
            line-height: 1.2;
            border: 1px solid rgba(128, 128, 128, 0.4);
            border-radius: 20px;
            transition: background .2s, color .2s, border-color .2s;
        }

        #aside .list-links li a:hover,
        #aside .list-links li a:active {
            background: #F8694A;
            border-color: #F8694A;
            color: #fff;
            text-decoration: none;
        }
    }
    </style>
    <!-- ===== END ASIDE RESPONSIVE ===== -->
    <!-- ===== ANIMASI IKON PANEL KATEGORI & MENU (panel-icon-luxe) ===== -->
    <style>
    .category-header i,
    .menu-header i {
        display: inline-block;
        transform-origin: center;
        /* ikon putih dengan sapuan kilau emas */
        background-image: linear-gradient(105deg,
            #ffffff 0%, #ffffff 38%,
            #ffe9a8 46%, #ffc94d 50%, #ffe9a8 54%,
            #ffffff 62%, #ffffff 100%);
        background-size: 300% 100%;
        background-position: 120% 0;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
        animation:
            luxeShine 4.5s cubic-bezier(.45, 0, .25, 1) infinite,
            luxeGlow 3.2s ease-in-out infinite;
        transition: transform .5s cubic-bezier(.22, 1, .36, 1);
        will-change: background-position, filter, transform;
    }

    .menu-header i {
        animation-delay: 1.2s, .6s;
    }

    .category-header:hover i,
    .menu-header:hover i,
    .category-header:active i,
    .menu-header:active i {
        transform: scale(1.22);
        animation:
            luxeShine 1.6s cubic-bezier(.45, 0, .25, 1) infinite,
            luxeGlowHover 1.8s ease-in-out infinite;
    }

    @keyframes luxeShine {
        0%   { background-position: 120% 0; }
        55%  { background-position: -20% 0; }
        100% { background-position: -20% 0; }
    }

    @keyframes luxeGlow {
        0%, 100% { filter: drop-shadow(0 0 1px rgba(255, 220, 140, .25)); }
        50%      { filter: drop-shadow(0 0 7px rgba(255, 210, 122, .85)); }
    }

    @keyframes luxeGlowHover {
        0%, 100% { filter: drop-shadow(0 0 5px rgba(255, 210, 122, .8)); }
        50%      { filter: drop-shadow(0 0 12px rgba(255, 190, 80, 1)); }
    }

    @media (prefers-reduced-motion: reduce) {
        .category-header i,
        .menu-header i {
            animation: none !important;
            transition: none !important;
            background-image: none;
            -webkit-text-fill-color: currentColor;
            color: inherit;
        }
    }
    </style>
    <!-- ===== END ANIMASI IKON PANEL ===== -->
    <!-- ===== ANIMASI IKON PANEL KATEGORI & MENU (panel-icon-anim) ===== -->
    <style>
    .category-header i,
    .menu-header i {
        display: inline-block;
        transform-origin: center;
        animation: panelIconWiggle 2.5s ease-in-out infinite;
    }

    .menu-header i {
        animation-delay: .4s;
    }

    .category-header:hover i,
    .menu-header:hover i {
        animation: panelIconSpin .6s linear infinite;
    }

    @keyframes panelIconWiggle {
        0%, 50%, 100% { transform: rotate(0deg); }
        10% { transform: rotate(-15deg); }
        20% { transform: rotate(15deg); }
        30% { transform: rotate(-10deg); }
        40% { transform: rotate(10deg); }
    }

    @keyframes panelIconSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @media (prefers-reduced-motion: reduce) {
        .category-header i,
        .menu-header i {
            animation: none !important;
        }
    }
    </style>
    <!-- ===== END ANIMASI IKON PANEL ===== -->
    <!-- ===== HEADER IKUT SCROLL (header-fixed-follow) ===== -->
    <style>
    #siteHeader {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        z-index: 1030 !important;
    }
    </style>
    <script>
    (function() {
        var header = document.getElementById('siteHeader');
        if (!header) return;

        function atur() {
            document.body.style.paddingTop = header.offsetHeight + 'px';
        }

        atur();
        window.addEventListener('load', atur);
        window.addEventListener('resize', atur);
        window.addEventListener('orientationchange', atur);
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(atur);
        if (window.ResizeObserver) new ResizeObserver(atur).observe(header);
    })();
    </script>
    <!-- ===== END HEADER IKUT SCROLL ===== -->
</body>

</html>