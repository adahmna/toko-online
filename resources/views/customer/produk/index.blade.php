@extends('v_layouts.app')

@section('content')
<style>
.tk {
    max-width: 1100px;
    margin: 0 auto;
}

.tk-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.tk-header .icon-wrap {
    background: #1976d2;
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.tk-header h4 {
    margin: 0;
    font-weight: 700;
    color: #1e2a3a;
    font-size: 20px;
}

.tk-main {
    width: 100%;
}

.tk-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    gap: 10px;
    flex-wrap: wrap;
}

.tk-count {
    font-size: 13px;
    color: #6b7280;
}

.tk-controls {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}

.tk-searchbox {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    border: 1px solid #e8eaf0;
    border-radius: 8px;
    padding: 0 12px;
    height: 36px;
}

.tk-searchbox i {
    color: #9ca3af;
    font-size: 14px;
}

.tk-searchbox input {
    border: none;
    outline: none;
    font-size: 13px;
    background: transparent;
    color: #1e2a3a;
    width: 160px;
}

.tk-sort {
    border: 1px solid #e8eaf0;
    border-radius: 8px;
    padding: 0 10px;
    height: 36px;
    font-size: 13px;
    background: #fff;
    color: #374151;
    outline: none;
    cursor: pointer;
}

.tk-grid {
    margin: 0 -10px;
    max-height: 700px;
    overflow-y: auto;
    padding: 10px 10px 10px 0;
    scroll-behavior: smooth;
}

.tk-grid::-webkit-scrollbar {
    width: 6px;
}
.tk-grid::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 3px;
}

.tk-grid .tk-card {
    padding: 0 10px;
    margin-bottom: 20px;
}

.tk-empty {
    width: 100%;
    text-align: center;
    padding: 60px 20px;
    color: #aaa;
}

.tk-empty i {
    font-size: 52px;
    display: block;
    margin-bottom: 14px;
    color: #ddd;
}

/* Foto produk lebih besar karena sidebar filter dihapus, konten jadi full-width */
.tk-grid .product-thumb {
    position: relative;
    height: 280px;
    overflow: hidden;
}

.tk-grid .product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.tk-grid .tk-card:nth-child(3n+1) {
    clear: left;
}

@media (max-width: 767px) {
    .tk-grid .product-thumb {
        height: 220px;
    }
    .tk-grid .tk-card:nth-child(3n+1) {
        clear: none;
    }

    .tk-grid .tk-card:nth-child(2n+1) {
        clear: left;
    }
}
</style>

<div class="tk">

    {{-- Header --}}
    <div class="tk-header">
        <div class="icon-wrap"><i class="fa fa-shopping-bag"></i></div>
        <h4>{{ $judul }}</h4>
    </div>

    @if($index->isEmpty())
    <div class="empty-state" style="text-align:center;padding:60px 20px;color:#aaa;">
        <i class="fa fa-cube" style="font-size:56px;display:block;margin-bottom:16px;color:#ddd;"></i>
        <p>Belum ada produk tersedia.</p>
    </div>
    @else

    <div class="tk-main">
        <div class="tk-topbar">
            <span class="tk-count" id="jml-produk"></span>
            <div class="tk-controls">
                <div class="tk-searchbox">
                    <i class="fa fa-search"></i>
                    <input type="text" id="cari" placeholder="Cari produk..." oninput="doFilter()">
                </div>
                <select class="tk-sort" id="sortby" onchange="doFilter()">
                    <option value="">Urutkan</option>
                    <option value="az">Nama A–Z</option>
                    <option value="za">Nama Z–A</option>
                    <option value="murah">Harga Termurah</option>
                    <option value="mahal">Harga Termahal</option>
                </select>
            </div>
        </div>

        {{-- STORE: kartu produk pakai desain sama seperti di beranda --}}
        <div class="row tk-grid" id="tk-grid">
            @foreach ($index as $row)
            @php $gambar = $row->gambar->first(); @endphp
            <div class="col-md-4 col-sm-6 col-xs-6 tk-card" data-nama="{{ strtolower($row->nama_produk) }}"
                data-harga="{{ $row->harga }}">

                <div class="product product-single">
                    <div class="product-thumb">
                        <div class="product-label">
                            <span>Kategori</span>
                            <span class="sale">{{ $row->kategori->nama_kategori ?? '-' }}</span>
                        </div>
                        <a href="{{ route('produk.detail', $row->id) }}">
                            <button class="main-btn quick-view"><i class="fa fa-search-plus"></i> Detail
                                Produk</button>
                        </a>
                        @if($gambar)
                        <img src="{{ asset('storage/img-produk/' . $gambar->foto) }}" alt="{{ $row->nama_produk }}">
                        @else
                        <img src="{{ asset('frontend/img/thumb-product01.jpg') }}" alt="{{ $row->nama_produk }}">
                        @endif
                    </div>
                    <div class="product-body">
                        <h3 class="product-price">Rp. {{ number_format($row->harga, 0, ',', '.') }}</h3>
                        <h2 class="product-name">
                            <a href="{{ route('produk.detail', $row->id) }}">{{ $row->nama_produk }}</a>
                        </h2>
                        <div class="product-btns">
                            <a href="{{ route('produk.detail', $row->id) }}" title="Detail Produk">
                                <button class="main-btn icon-btn"><i class="fa fa-search-plus"></i></button>
                            </a>
                            <form action="{{ route('order.addToCart', $row->id) }}" method="post"
                                style="display: inline-block;" title="Pesan Ke Aplikasi">
                                @csrf
                                <button type="submit" class="primary-btn add-to-cart"
                                    {{ $row->stok == 0 ? 'disabled' : '' }}>
                                    <i class="fa fa-shopping-cart"></i>
                                    {{ $row->stok == 0 ? 'Stok Habis' : 'Pesan' }}
                                </button>
                            </form>

                            @auth
                            <form action="{{ route('wishlist.store', $row->id) }}" method="post" style="display:inline-block;" title="Tambah ke Wishlist">
                                @csrf
                                <button type="submit" class="main-btn icon-btn">
                                    <i class="fa fa-heart-o"></i>
                                </button>
                            </form>
                            @endauth

                            <form action="{{ route('bandingkan.store', $row->id) }}" method="post" style="display:inline-block;" title="Tambah ke Perbandingan">
                                @csrf
                                <button type="submit" class="main-btn icon-btn">
                                    <i class="fa fa-balance-scale"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach

            <div class="tk-empty" id="tk-empty" style="display:none;">
                <i class="fa fa-cube"></i>
                <p>Produk tidak ditemukan.</p>
            </div>
        </div>{{-- /tk-grid --}}

    </div>{{-- /tk-main --}}
    @endif

</div>{{-- /tk --}}

<script>
function doFilter() {
    var q = document.getElementById('cari').value.toLowerCase();
    var sort = document.getElementById('sortby').value;
    var cards = Array.from(document.querySelectorAll('.tk-card'));
    var visible = [];

    cards.forEach(function(c) {
        var show = c.dataset.nama.includes(q);
        c.style.display = show ? '' : 'none';
        if (show) visible.push(c);
    });

    if (sort && visible.length > 1) {
        var grid = document.getElementById('tk-grid');
        visible.sort(function(a, b) {
            if (sort === 'az') return a.dataset.nama.localeCompare(b.dataset.nama);
            if (sort === 'za') return b.dataset.nama.localeCompare(a.dataset.nama);
            if (sort === 'murah') return parseInt(a.dataset.harga) - parseInt(b.dataset.harga);
            if (sort === 'mahal') return parseInt(b.dataset.harga) - parseInt(a.dataset.harga);
        });
        visible.forEach(function(c) {
            grid.appendChild(c);
        });
    }

    document.getElementById('tk-empty').style.display = visible.length === 0 ? 'block' : 'none';
    document.getElementById('jml-produk').textContent = visible.length + ' produk ditampilkan';
}

window.addEventListener('DOMContentLoaded', function() {
    var total = document.querySelectorAll('.tk-card').length;
    document.getElementById('jml-produk').textContent = total + ' produk ditampilkan';
});
</script>

@endsection