@extends('v_layouts.app')
@section('content')
<style>
.product-thumb {
    position: relative;
    height: 220px;
    overflow: hidden;
}
.product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
</style>
<div class="container" style="padding: 40px 0;">
    <div class="text-center" style="margin-bottom: 30px;">
        <i class="fa fa-heart" style="font-size: 40px; color: #F8694A;"></i>
        <h2 style="margin-top: 15px;">Wishlist Saya</h2>
        <p style="color: #888;">Produk favorit yang kamu simpan buat dibeli nanti.</p>
    </div>

    @if (session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if ($items->isEmpty())
    <div class="text-center" style="padding: 60px 0;">
        <i class="fa fa-heart-o" style="font-size: 60px; color: #ddd;"></i>
        <h3 style="margin-top: 20px; color: #999;">Wishlist kamu masih kosong</h3>
        <p style="color: #aaa;">Yuk mulai simpan produk favoritmu dengan menekan tombol hati di halaman produk.</p>
        <a href="{{ route('customer.produk.index') }}" class="primary-btn" style="margin-top: 15px; display: inline-block;">Jelajahi Produk</a>
    </div>
    @else
    <div class="row">
        @foreach ($items as $item)
        @php $row = $item->produk; @endphp
        @if ($row)
        @php $gambar = $row->gambar->first(); @endphp
        <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="product product-single">
                <div class="product-thumb">
                    <div class="product-label">
                        <span>Kategori</span>
                        <span class="sale">{{ $row->kategori->nama_kategori ?? '-' }}</span>
                    </div>
                    <a href="{{ route('produk.detail', $row->id) }}">
                        <button class="main-btn quick-view"><i class="fa fa-search-plus"></i> Detail Produk</button>
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
                        <form action="{{ route('wishlist.destroy', $item->id) }}" method="post" style="display:inline;" onsubmit="return confirm('Hapus dari wishlist?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="main-btn icon-btn" title="Hapus dari Wishlist">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif
</div>
@endsection