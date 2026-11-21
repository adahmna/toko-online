@extends('v_layouts.app')
@section('content')
<style>
.order-summary { max-width: 100%; }

@media (max-width: 767px) {
    .order-summary { padding-left: 0; padding-right: 0; }
    .order-summary .row { margin-left: 0; margin-right: 0; }
    .order-summary .table-responsive { overflow-x: visible; border: 0; }

    /* tabel produk jadi kartu */
    .konfirmasi-tabel thead { display: none; }
    .konfirmasi-tabel,
    .konfirmasi-tabel tbody { display: block; width: 100%; }
    .konfirmasi-tabel tr {
        display: grid;
        grid-template-columns: 1fr auto;
        column-gap: 12px;
        row-gap: 6px;
        padding: 12px;
        margin-bottom: 12px;
        border: 1px solid #e4e7ed;
        border-radius: 6px;
        background: #fff;
    }
    .konfirmasi-tabel td {
        display: block;
        width: auto !important;
        padding: 0 !important;
        border: 0 !important;
        text-align: left !important;
    }
    .konfirmasi-tabel td.details { grid-column: 1 / -1; word-break: break-word; }
    .konfirmasi-tabel td.price { grid-column: 1; }
    .konfirmasi-tabel td.qty { grid-column: 2; text-align: right !important; }
    .konfirmasi-tabel td.qty::before { content: 'x '; }
    .konfirmasi-tabel td.total { grid-column: 1 / -1; }
    .konfirmasi-tabel td.total::before { content: 'Total: '; font-weight: 400; color: #666; }

    /* tabel subtotal / ongkir / total bayar */
    .konfirmasi-ringkasan { width: 100%; table-layout: fixed; }
    .konfirmasi-ringkasan td { word-break: break-word; }

    /* tombol bayar */
    .konfirmasi-bayar { float: none !important; width: 100%; }
    .konfirmasi-bayar .primary-btn { display: block; width: 100%; text-align: center; }
}
</style>
<div class="col-md-12">
    <div class="order-summary clearfix">
        <div class="section-title">
            <p>RINGKASAN</p>
            <h3 class="title">Keranjang Belanja</h3>
        </div>

        <div class="table-responsive">
            <table class="shopping-cart-table table konfirmasi-tabel">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="details">
                            <a>{{ $item->produk->nama_produk }}</a>
                            <ul>
                                <li><span>Berat: {{ $item->produk->berat }} Gram</span></li>
                            </ul>
                            <ul>
                                <li><span>Stok: {{ $item->produk->stok }}</span></li>
                            </ul>
                        </td>
                        <td class="price text-center">
                            <strong>Rp. {{ number_format($item->harga, 0, ',', '.') }}</strong>
                        </td>
                        <td class="qty text-center">{{ $item->jumlah }}</td>
                        <td class="total text-center">
                            <strong class="primary-color">
                                Rp. {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end" style="margin-top:15px;">
            <div class="col-md-5">
                <table class="table konfirmasi-ringkasan">
                    <tbody>
                        <tr>
                            <td>Subtotal</td>
                            <td class="text-right"><strong>Rp. {{ number_format($subtotal, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td>Ongkos Kirim</td>
                            <td class="text-right">
                                <div>Rp. {{ number_format($ongkir, 0, ',', '.') }}</div>
                                <small class="text-muted">
                                    {{ strtolower($ekspedisi) }}.{{ $layanan }}
                                    @if($estimasi) *estimasi {{ $estimasi }} @endif
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total Bayar</strong></td>
                            <td class="text-right"><strong class="primary-color" style="font-size:18px;">
                                    Rp. {{ number_format($total, 0, ',', '.') }}
                                </strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form ini yang benar-benar menyimpan pesanan lalu lanjut ke halaman pembayaran (timer) --}}
        <form action="{{ route('order.pilihPengiriman') }}" method="POST" id="form-bayar">
            @csrf
            <input type="hidden" name="kota_tujuan" value="{{ $kota_tujuan }}">
            <input type="hidden" name="id_kota_tujuan" value="{{ $id_kota_tujuan }}">
            <input type="hidden" name="ekspedisi" value="{{ $ekspedisi }}">
            <input type="hidden" name="layanan" value="{{ $layanan }}">
            <input type="hidden" name="ongkir" value="{{ $ongkir }}">
            <input type="hidden" name="alamat" value="{{ $alamat }}">
            <input type="hidden" name="kode_pos" value="{{ $kode_pos }}">

            <div class="pull-right konfirmasi-bayar" style="margin-top:15px;">
                <button type="submit" class="primary-btn" id="btn-bayar">Bayar Sekarang</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('form-bayar').addEventListener('submit', function() {
    const btn = document.getElementById('btn-bayar');
    btn.disabled = true;
    btn.innerText = 'Memproses...';
});
</script>
@endsection