@extends('v_layouts.app')
@section('content')
<style>
.wl-fullbleed {
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    width: 100vw;
    padding: 40px 20px;
}
.wl-header { text-align: center; max-width: 500px; margin: 0 auto 20px; }
.wl-carousel-wrap {
    position: relative;
    max-width: 560px;
    margin: 0 auto;
    padding: 50px 0;
}
.wl-track {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    overflow-y: auto;
    scroll-behavior: smooth;
    scroll-snap-type: y mandatory;
    max-height: 620px;
    padding: 4px;
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.wl-track::-webkit-scrollbar { display: none; }
.wl-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
    position: relative;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    scroll-snap-align: start;
}
.wl-card img {
    width: 100%; height: 130px; object-fit: cover;
    border-radius: 8px; margin-bottom: 12px;
}
.wl-label {
    display: inline-block;
    background: #F8694A;
    color: #fff;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 4px;
    margin-bottom: 10px;
}
.wl-card h4 { font-size: 14px; margin: 0 0 8px; }
.wl-price { color: #F8694A; font-weight: 700; font-size: 15px; margin-bottom: 12px; }
.wl-btns { display: flex; gap: 8px; }
.wl-btns form { flex: 1; }
.wl-btns button, .wl-btns a button { width: 100%; }
.wl-nav {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    border: 1px solid #eee;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #F8694A;
    z-index: 2;
}
.wl-nav:hover { background: #F8694A; color: #fff; }
.wl-prev { top: 0; }
.wl-next { bottom: 0; }

/* ===== TAMPILAN HP / TABLET ===== */
@media (max-width: 991px) {
    /* matikan trik "full-bleed" (100vw + left 50%) yang bikin konten bergeser/terpotong */
    .wl-fullbleed {
        left: 0;
        right: 0;
        margin-left: 0;
        margin-right: 0;
        width: 100%;
        max-width: 100%;
        padding: 24px 12px;
        box-sizing: border-box;
    }

    .wl-header h2 { font-size: 22px; }

    .wl-carousel-wrap { padding: 44px 0; }

    .wl-track {
        gap: 12px;
        max-height: 560px;
    }

    .wl-card { padding: 12px; }
    .wl-card img { height: 110px; }
}

/* HP kecil: satu kartu per baris */
@media (max-width: 420px) {
    .wl-track { grid-template-columns: 1fr; }
    .wl-card img { height: 150px; }
}
</style>

<div class="wl-fullbleed">
    <div class="wl-header">
        <i class="fa fa-heart" style="font-size: 40px; color: #F8694A;"></i>
        <h2 style="margin-top: 15px;">Wishlist Saya</h2>
        <p style="color: #888;">Produk favorit yang kamu simpan buat dibeli nanti.</p>
    </div>

    @if (session('success'))
    <div class="alert alert-success text-center" style="max-width:560px;margin:0 auto;">{{ session('success') }}</div>
    @endif

    @if ($items->isEmpty())
    <div class="text-center" style="padding: 60px 0;">
        <i class="fa fa-heart-o" style="font-size: 60px; color: #ddd;"></i>
        <h3 style="margin-top: 20px; color: #999;">Wishlist kamu masih kosong</h3>
        <p style="color: #aaa;">Yuk mulai simpan produk favoritmu dengan menekan tombol hati di halaman produk.</p>
        <a href="{{ route('customer.produk.index') }}" class="primary-btn" style="margin-top: 15px; display: inline-block;">Jelajahi Produk</a>
    </div>
    @else
    <div class="wl-carousel-wrap">
        <div class="wl-nav wl-prev" onclick="wlScroll(-1)"><i class="fa fa-chevron-up"></i></div>
        <div class="wl-nav wl-next" onclick="wlScroll(1)"><i class="fa fa-chevron-down"></i></div>

        <div class="wl-track" id="wl-track">
            @foreach ($items as $item)
            @php $row = $item->produk; @endphp
            @if ($row)
            @php $gambar = $row->gambar->first(); @endphp
            <div class="wl-card">
                <span class="wl-label">{{ $row->kategori->nama_kategori ?? '-' }}</span>

                @if($gambar)
                <img src="{{ asset('storage/img-produk/' . $gambar->foto) }}" alt="{{ $row->nama_produk }}">
                @else
                <img src="{{ asset('frontend/img/thumb-product01.jpg') }}" alt="{{ $row->nama_produk }}">
                @endif

                <h4><a href="{{ route('produk.detail', $row->id) }}" style="color:#333;">{{ $row->nama_produk }}</a></h4>
                <div class="wl-price">Rp. {{ number_format($row->harga, 0, ',', '.') }}</div>

                <div class="wl-btns">
                    <a href="{{ route('produk.detail', $row->id) }}" title="Detail Produk">
                        <button class="main-btn icon-btn"><i class="fa fa-search-plus"></i></button>
                    </a>
                    <form action="{{ route('wishlist.destroy', $item->id) }}" method="post" onsubmit="return confirm('Hapus dari wishlist?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="main-btn icon-btn" title="Hapus dari Wishlist">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    <script>
    function wlScroll(dir) {
        var track = document.getElementById('wl-track');
        var card = track.querySelector('.wl-card');
        var gap = 20;
        var rowHeight = card ? card.offsetHeight + gap : 300;
        track.scrollBy({ top: dir * rowHeight, behavior: 'smooth' });
    }
    </script>
    @endif
</div>
@endsection