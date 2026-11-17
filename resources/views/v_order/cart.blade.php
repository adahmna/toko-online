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
                    @php
                    $totalHarga = 0;
                    $totalBerat = 0;
                    @endphp

                    @foreach($order->orderItems as $item)
                    @php
                    $totalHarga += $item->harga * $item->jumlah;
                    $totalBerat += $item->produk->berat * $item->jumlah;
                    @endphp

                    <tr>
                        <td class="thumb">
                            <img src="{{ asset('storage/img-produk/thumb_sm_' . $item->produk->foto) }}" alt="">
                        </td>
                        <td class="details">
                            <a>{{ $item->produk->nama_produk }}</a>
                            <ul>
                                <li><span>Berat: {{ $item->produk->berat }} Gram</span></li>
                            </ul>
                            <ul>
                                <li><span>Stok: {{ $item->produk->stok }} Gram</span></li>
                            </ul>
                        </td>
                        <td class="price text-center">
                            <strong>Rp. {{ number_format($item->harga, 0, ',', '.') }}</strong>
                        </td>
                        <td class="qty text-center">
                            <form action="{{ route('order.updateItem', $item->id) }}" method="post">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1"
                                    style="width: 60px;">
                                <button type="submit" class="btn btn-sm btn-warning">Update</button>
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

        <div class="pull-right cart-checkout">
            <a href="{{ route('order.formPengiriman') }}" class="primary-btn">Pilih Pengiriman</a>
        </div>
        @else
        <p>Keranjang belanja kosong.</p>
        @endif
    </div>
</div>
<!-- end template -->
@endsection