@extends('v_layouts.app')

@section('content')
<!-- template -->
<style>
.cart-scroll-box {
    max-height: 480px;
    overflow-y: auto;
    scroll-behavior: smooth;
}

.cart-scroll-box::-webkit-scrollbar {
    width: 6px;
}

.cart-scroll-box::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 3px;
}

.cart-scroll-box table thead th {
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 2;
}

/* ===== PRODUK TIDAK TERSEDIA / PERUBAHAN HARGA & JUMLAH ===== */
.shopping-cart-table tr.item-tidak-tersedia {
    background: #f5f5f5;
}

.shopping-cart-table tr.item-tidak-tersedia td.thumb img,
.shopping-cart-table tr.item-tidak-tersedia td.price,
.shopping-cart-table tr.item-tidak-tersedia td.qty,
.shopping-cart-table tr.item-tidak-tersedia td.total {
    opacity: 0.5;
}

.shopping-cart-table tr.item-tidak-tersedia td.thumb img {
    filter: grayscale(100%);
}

.shopping-cart-table tr.item-tidak-tersedia td.details a {
    color: #999;
}

.badge-tidak-tersedia {
    display: inline-block;
    margin-top: 6px;
    padding: 2px 10px;
    border-radius: 12px;
    background: #f8d7da;
    color: #721c24;
    font-size: 12px;
    font-weight: 700;
}

.catatan-item {
    margin-top: 6px;
    font-size: 12px;
    color: #8a6d3b;
}

.harga-lama {
    display: block;
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
}

.cart-subtotal {
    margin: 12px 0;
    text-align: right;
    font-size: 15px;
}

.primary-btn.btn-nonaktif {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.catatan-checkout {
    margin-top: 8px;
    font-size: 12px;
    color: #a94442;
    text-align: right;
}

/* ===== TAMPILAN HP: tiap produk jadi satu kartu ===== */
@media (max-width: 767px) {
    .order-summary {
        padding-left: 0;
        padding-right: 0;
    }

    .cart-scroll-box {
        max-height: none;
        overflow: visible;
    }

    .cart-scroll-box table thead {
        display: none;
    }

    .shopping-cart-table,
    .shopping-cart-table tbody {
        display: block;
        width: 100%;
    }

    .shopping-cart-table tr {
        display: grid;
        grid-template-columns: 80px 1fr;
        column-gap: 12px;
        row-gap: 8px;
        position: relative;
        padding: 12px 40px 12px 12px;
        margin-bottom: 12px;
        border: 1px solid #e4e7ed;
        border-radius: 6px;
        background: #fff;
    }

    .shopping-cart-table td {
        display: block;
        width: auto !important;
        padding: 0 !important;
        border: 0 !important;
        text-align: left !important;
    }

    .shopping-cart-table td.thumb {
        grid-column: 1;
        grid-row: 1 / span 2;
    }

    .shopping-cart-table td.thumb img {
        width: 80px;
        height: auto;
    }

    .shopping-cart-table td.details {
        grid-column: 2;
        grid-row: 1;
        min-width: 0;
        word-break: break-word;
    }

    .shopping-cart-table td.price {
        grid-column: 2;
        grid-row: 2;
    }

    .shopping-cart-table td.qty,
    .shopping-cart-table td.total {
        grid-column: 1 / -1;
    }

    .shopping-cart-table td.qty form {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .shopping-cart-table td.total::before {
        content: 'Total: ';
        font-weight: 400;
        color: #666;
    }

    /* tombol hapus di pojok kanan atas kartu */
    .shopping-cart-table td.text-right {
        position: absolute;
        top: 8px;
        right: 8px;
    }

    .cart-checkout {
        float: none !important;
        width: 100%;
    }

    .cart-checkout .primary-btn {
        display: block;
        width: 100%;
        text-align: center;
    }

    .catatan-checkout {
        text-align: center;
    }
}
</style>
<div class="col-md-12">
    <div class="order-summary clearfix">
        <div class="section-title">
            <p>KERANJANG</p>
            <h3 class="title">Keranjang Belanja</h3>
        </div>

        {{-- msgSuccess --}}
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>{{ session('success') }}</strong>
        </div>
        @endif
        {{-- end msgSuccess --}}

        {{-- msgError --}}
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>{{ session('error') }}</strong>
        </div>
        @endif
        {{-- end msgError --}}

        @if($order && $order->orderItems->count() > 0)

        @php
        // ada produk yang tidak tersedia? (flag "tersedia" diisi OrderController::sinkronkanKeranjang)
        $adaTidakTersedia = $order->orderItems->contains(fn ($i) => ($i->tersedia ?? true) === false);
        $totalHarga = 0;
        $totalBerat = 0;
        @endphp

        <div class="cart-scroll-box">
            <table class="shopping-cart-table table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th></th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Total</th>
                        <th class="text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    @php
                    $tersedia = $item->tersedia ?? true;
                    $produk = $item->produk;

                    // hanya produk yang tersedia yang dihitung
                    if ($tersedia && $produk) {
                    $totalHarga += $item->harga * $item->jumlah;
                    $totalBerat += $produk->berat * $item->jumlah;
                    }
                    @endphp

                    <tr class="{{ $tersedia ? '' : 'item-tidak-tersedia' }}">
                        <td class="thumb">
                            @if($produk && $produk->foto)
                            <img src="{{ asset('storage/img-produk/thumb_sm_' . $produk->foto) }}" alt="">
                            @endif
                        </td>
                        <td class="details">
                            <a>{{ $produk->nama_produk ?? $item->nama_produk }}</a>

                            @if(!$tersedia)
                            <div><span class="badge-tidak-tersedia">Produk tidak tersedia</span></div>
                            @endif

                            @if($produk)
                            <ul>
                                <li><span>Berat: {{ $produk->berat }} Gram</span></li>
                            </ul>
                            <ul>
                                <li><span>Stok: {{ $produk->stok }}</span></li>
                            </ul>
                            @endif

                            @if($tersedia && ($item->harga_berubah ?? false))
                            <div class="catatan-item">
                                Harga {{ $item->harga > $item->harga_lama ? 'naik' : 'turun' }} dari
                                Rp. {{ number_format($item->harga_lama, 0, ',', '.') }}
                            </div>
                            @endif

                            @if($tersedia && ($item->jumlah_disesuaikan ?? false))
                            <div class="catatan-item">
                                Jumlah disesuaikan, stok tersisa {{ $item->stok_tersedia }}
                            </div>
                            @endif
                        </td>
                        <td class="price text-center">
                            @if($tersedia && ($item->harga_berubah ?? false))
                            <span class="harga-lama">Rp. {{ number_format($item->harga_lama, 0, ',', '.') }}</span>
                            @endif
                            <strong>Rp. {{ number_format($item->harga, 0, ',', '.') }}</strong>
                        </td>
                        <td class="qty text-center">
                            <form action="{{ route('order.updateItem', $item->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1" @if($tersedia &&
                                    $produk) max="{{ $produk->stok }}" @endif style="width: 60px;"
                                    {{ $tersedia ? '' : 'disabled' }}>
                                <button type="submit" class="btn btn-sm btn-warning"
                                    {{ $tersedia ? '' : 'disabled' }}>Update</button>
                            </form>
                        </td>
                        <td class="total text-center">
                            <strong class="primary-color">
                                Rp. {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td class="text-right">
                            <form action="{{ route('order.removeItem', $item->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="main-btn icon-btn"><i class="fa fa-close"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="cart-subtotal">
            Subtotal{{ $adaTidakTersedia ? ' (produk yang tersedia)' : '' }}:
            <strong class="primary-color">Rp. {{ number_format($totalHarga, 0, ',', '.') }}</strong>
        </div>

        <div class="pull-right cart-checkout">
            @if($adaTidakTersedia)
            <span class="primary-btn btn-nonaktif">Pilih Pengiriman</span>
            <div class="catatan-checkout">Hapus produk yang tidak tersedia untuk melanjutkan.</div>
            @else
            <a href="{{ route('order.formPengiriman') }}" class="primary-btn">Pilih Pengiriman</a>
            @endif
        </div>
        @else
        <p>Keranjang belanja kosong.</p>
        @endif
    </div>
</div>
<!-- end template -->
@endsection