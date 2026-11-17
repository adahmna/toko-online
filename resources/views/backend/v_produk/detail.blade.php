@extends('v_layouts.app')

@section('content')
<div class="container my-4">
    <h1 class="h3 mb-4">{{ $judul }}</h1>

    <div class="row">
        <!-- Bagian Gambar Produk -->
        <div class="col-md-5">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <!-- Gambar Utama Produk -->
                    <img src="{{ asset('storage/img/produk/' . $row->foto) }}" class="img-fluid rounded"
                        alt="{{ $row->nama_produk }}">
                </div>
            </div>

            <!-- Foto Produk Tambahan / Galeri -->
            <div class="row">
                @foreach ($fotoProdukTambahan as $item)
                @if ($item->produk_id == $row->id)
                <div class="col-4 mb-2">
                    <img src="{{ asset('storage/img/produk/tambahan/' . $item->foto) }}" class="img-thumbnail"
                        alt="Foto Tambahan">
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Bagian Informasi & Detail Produk -->
        <div class="col-md-7">
            <span class="badge bg-secondary mb-2">{{ $row->kategori->nama_kategori }}</span>
            <h2 class="fw-bold">{{ $row->nama_produk }}</h2>
            <h3 class="text-primary mb-3">Rp. {{ number_format($row->harga, 0, ',', '.') }}</h3>

            <div class="mb-3">
                <strong>Deskripsi:</strong>
                <div>{!! $row->detail !!}</div>
            </div>

            <ul class="list-unstyled mb-4">
                <li><strong>Berat:</strong> {{ $row->berat }} Gram</li>
                <li><strong>Stok:</strong> {{ $row->stok }} tersedia</li>
            </ul>

            <!-- Form Pemesanan / Keranjang -->
            <form action="{{ route('cart.store', $row->id) }}" method="POST">
                @csrf
                <div class="input-group mb-3" style="max-width: 200px;">
                    <input type="number" name="jumlah" class="form-control text-center" value="1" min="1"
                        max="{{ $row->stok }}">
                    <button class="btn btn-success" type="submit">Pesan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
