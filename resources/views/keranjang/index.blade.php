@extends('backend.v_layouts.app')
@section('content')

<div class="row">
    <div class="col-12">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Keranjang Belanja</h5>

                @if($keranjangs->isEmpty())
                <div class="alert alert-info">Keranjang masih kosong.</div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($keranjangs as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                                <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('customer.keranjang.update', $item->id) }}" method="POST"
                                        class="d-flex gap-1">
                                        @csrf @method('PATCH')
                                        <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1"
                                            class="form-control form-control-sm" style="width:70px">
                                        <button class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                                <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('customer.keranjang.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus produk ini dari keranjang?')">
                                            <i class="far fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total:</th>
                                <th>Rp. {{ number_format($keranjangs->sum('subtotal'), 0, ',', '.') }}</th>
                                <th>
                                    <div class="d-flex gap-2">
                                        {{-- Kosongkan --}}
                                        <form action="{{ route('customer.keranjang.clear') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-warning"
                                                onclick="return confirm('Yakin ingin mengosongkan semua keranjang?')">
                                                Kosongkan Semua
                                            </button>
                                        </form>

                                        {{-- Checkout --}}
                                        <a href="{{ route('customer.keranjang.checkout') }}"
                                            class="btn btn-sm btn-success">
                                            <i class="mdi mdi-cart-arrow-right"></i> Checkout
                                        </a>
                                    </div>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif

                <a href="{{ route('customer.produk.index') }}" class="btn btn-secondary mt-2">
                    ← Kembali ke Produk
                </a>

            </div>
        </div>
    </div>
</div>

@endsection
