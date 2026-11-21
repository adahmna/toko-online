@extends('backend.v_layouts.app')
@section('judul', 'Konfirmasi Pesanan')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="mdi mdi-cart-check"></i> Konfirmasi Pesanan</h5>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($keranjangs as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                                <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th colspan="4" class="text-end">TOTAL:</th>
                                <th>Rp. {{ number_format($total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-3" style="flex-wrap: wrap; gap: 8px;">
                    <a href="{{ route('customer.keranjang.index') }}" class="btn btn-secondary">
                        ← Kembali ke Keranjang
                    </a>

                    <form action="{{ route('customer.keranjang.proses-checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-check-circle"></i> Konfirmasi & Pesan Sekarang
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection