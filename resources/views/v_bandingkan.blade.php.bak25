@extends('v_layouts.app')
@section('content')
<style>
.bd-fullbleed {
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    width: 100vw;
    padding: 40px 20px;
}

.bd-header {
    text-align: center;
    max-width: 500px;
    margin: 0 auto 20px;
}

.bd-carousel-wrap {
    position: relative;
    max-width: 560px;
    margin: 0 auto;
    padding: 50px 0;
}

.bd-track {
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

.bd-track::-webkit-scrollbar {
    display: none;
}

.bd-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
    position: relative;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    scroll-snap-align: start;
}

.bd-card .bd-remove {
    position: absolute;
    top: 10px;
    right: 10px;
    border: none;
    background: none;
    color: #ccc;
    cursor: pointer;
    font-size: 16px;
}

.bd-card .bd-remove:hover {
    color: #F8694A;
}

.bd-card img {
    width: 100%;
    height: 130px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 12px;
}

.bd-card h4 {
    font-size: 14px;
    margin: 0 0 10px;
}

.bd-row {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    border-top: 1px solid #f2f2f2;
    font-size: 12px;
}

.bd-row span:first-child {
    color: #999;
}

.bd-row span:last-child {
    color: #333;
    font-weight: 600;
    text-align: right;
}

.bd-harga {
    color: #F8694A !important;
}

.bd-nav {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    border: 1px solid #eee;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

.bd-nav:hover {
    background: #F8694A;
    color: #fff;
}

.bd-prev {
    top: 0;
}

.bd-next {
    bottom: 0;
}

/* ===== TAMPILAN HP / TABLET ===== */
@media (max-width: 991px) {

    /* matikan trik "full-bleed" (100vw + left 50%) yang bikin konten bergeser/terpotong */
    .bd-fullbleed {
        left: 0;
        right: 0;
        margin-left: 0;
        margin-right: 0;
        width: 100%;
        max-width: 100%;
        padding: 24px 12px;
        box-sizing: border-box;
    }

    .bd-header h2 {
        font-size: 22px;
    }

    .bd-carousel-wrap {
        padding: 44px 0;
    }

    .bd-track {
        gap: 12px;
        max-height: 560px;
    }

    .bd-card {
        padding: 12px;
    }

    .bd-card img {
        height: 110px;
    }
}

/* HP kecil: satu kartu per baris */
@media (max-width: 420px) {
    .bd-track {
        grid-template-columns: 1fr;
    }

    .bd-card img {
        height: 150px;
    }
}
</style>

<div class="bd-fullbleed">
    <div class="bd-header">
        <i class="fa fa-balance-scale" style="font-size: 40px; color: #F8694A;"></i>
        <h2 style="margin-top: 15px;">Bandingkan Produk</h2>
        <p style="color: #888;">Lihat perbedaan produk berdampingan sebelum memutuskan beli.</p>
    </div>

    @if (session('success'))
    <div class="alert alert-success text-center" style="max-width:560px;margin:0 auto;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger text-center" style="max-width:560px;margin:0 auto;">{{ session('error') }}</div>
    @endif
    @if (session('info'))
    <div class="alert alert-info text-center" style="max-width:560px;margin:0 auto;">{{ session('info') }}</div>
    @endif

    @if ($produk->isEmpty())
    <div class="text-center" style="padding: 60px 0;">
        <i class="fa fa-balance-scale" style="font-size: 60px; color: #ddd;"></i>
        <h3 style="margin-top: 20px; color: #999;">Belum ada produk untuk dibandingkan</h3>
        <p style="color: #aaa;">Tambahkan produk dari halaman katalog untuk mulai membandingkan.</p>
        <a href="{{ route('customer.produk.index') }}" class="primary-btn"
            style="margin-top: 15px; display: inline-block;">Jelajahi Produk</a>
    </div>
    @else
    <div class="bd-carousel-wrap">
        <div class="bd-nav bd-prev" onclick="bdScroll(-1)"><i class="fa fa-chevron-up"></i></div>
        <div class="bd-nav bd-next" onclick="bdScroll(1)"><i class="fa fa-chevron-down"></i></div>

        <div class="bd-track" id="bd-track">
            @foreach ($produk as $item)
            @php $gambar = $item->gambar->first(); @endphp
            <div class="bd-card">
                <form action="{{ route('bandingkan.destroy', $item->id) }}" method="post"
                    onsubmit="return confirm('Hapus dari perbandingan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bd-remove" title="Hapus"><i class="fa fa-times-circle"></i></button>
                </form>

                @if ($gambar)
                <img src="{{ asset('storage/img-produk/' . $gambar->foto) }}" alt="{{ $item->nama_produk }}">
                @else
                <img src="{{ asset('frontend/img/thumb-product01.jpg') }}" alt="{{ $item->nama_produk }}">
                @endif

                <h4><a href="{{ route('produk.detail', $item->id) }}" style="color:#333;">{{ $item->nama_produk }}</a>
                </h4>

                <div class="bd-row"><span>Harga</span><span class="bd-harga">Rp
                        {{ number_format($item->harga, 0, ',', '.') }}</span></div>
                <div class="bd-row"><span>Kategori</span><span>{{ $item->kategori->nama_kategori ?? '-' }}</span></div>
                <div class="bd-row"><span>Stok</span><span>{{ $item->stok ?? '-' }}</span></div>
                <div class="bd-row" style="display:block;">
                    <span style="display:block; margin-bottom:4px;">Deskripsi</span>
                    <span
                        style="text-align:left; display:block; font-weight:400; color:#777; font-size:12px;">{{ Str::limit($item->deskripsi ?? '-', 80) }}</span>
                </div>

                <a href="{{ route('produk.detail', $item->id) }}" style="display:block; margin-top:14px;">
                    <button class="main-btn" style="width:100%;">Lihat Detail</button>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <script>
    function bdScroll(dir) {
        var track = document.getElementById('bd-track');
        var card = track.querySelector('.bd-card');
        var gap = 20;
        var rowHeight = card ? card.offsetHeight + gap : 300;
        track.scrollBy({
            top: dir * rowHeight,
            behavior: 'smooth'
        });
    }
    </script>
    @endif
</div>
@endsection