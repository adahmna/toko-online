@extends('backend.v_layouts.app')

@section('content')
<!-- contentAwal -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $judul }}</h4>

                    <div class="row">

                        {{-- ===== KOLOM KIRI ===== --}}
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="kategori_id"
                                    class="form-control @error('kategori_id') is-invalid @enderror" disabled>
                                    <option value="" selected>- Pilih Kategori -</option>
                                    @foreach ($kategori as $row)
                                    <option value="{{ $row->id }}"
                                        {{ old('kategori_id', $show->kategori_id) == $row->id ? 'selected' : '' }}>
                                        {{ $row->nama_kategori }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" name="nama_produk"
                                    value="{{ old('nama_produk', $show->nama_produk) }}" class="form-control"
                                    placeholder="Masukkan Nama Produk" disabled>
                            </div>

                            <div class="form-group">
                                <label>Detail</label>
                                <textarea name="detail" class="form-control" id="ckeditor"
                                    disabled>{{ old('detail', $show->detail) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Foto Utama</label><br>
                                <img src="{{ asset('storage/img-produk/' . $show->foto) }}"
                                    class="foto-preview img-fluid" width="100%">
                            </div>

                        </div>

                        {{-- ===== KOLOM KANAN ===== --}}
                        <div class="col-md-6">
                            <label>Foto Tambahan</label>

                            {{-- Daftar foto yang sudah ada --}}
                            <div id="foto-container">
                                @foreach($show->gambar->where('foto', '!=', $show->foto) as $gambar)
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-8">
                                        <img src="{{ asset('storage/img-produk/' . $gambar->foto) }}" width="100%"
                                            class="img-thumbnail">
                                    </div>
                                    <div class="col-md-4">
                                        <form action="{{ route('backend.foto_produk.destroy', $gambar->id) }}"
                                            method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin hapus foto?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Form tambah foto (tersembunyi dulu) --}}
                            <div id="form-tambah-foto" class="mt-3" style="display: none;">
                                <form action="{{ route('backend.foto_produk.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $show->id }}">

                                    <div class="form-group">
                                        {{-- ✅ PERBAIKAN: name="foto_produk[]" agar sesuai storeFoto() di controller --}}
                                        <input type="file" name="foto_produk[]" class="form-control-file"
                                            accept="image/jpeg,image/jpg,image/png,image/gif" required>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-sm">
                                        Simpan
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" id="btn-batal">
                                        Batal
                                    </button>
                                </form>
                            </div>

                            <button type="button" class="btn btn-primary mt-3" id="btn-tambah-foto">
                                Tambah Foto
                            </button>

                        </div>
                    </div>

                    <div class="border-top mt-3">
                        <div class="card-body">
                            <a href="{{ route('backend.produk.index') }}">
                                <button type="button" class="btn btn-secondary">
                                    Kembali
                                </button>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- contentAkhir -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const btnTambah = document.getElementById('btn-tambah-foto');
    const formTambah = document.getElementById('form-tambah-foto');
    const btnBatal = document.getElementById('btn-batal');

    btnTambah.addEventListener('click', function() {
        formTambah.style.display = 'block';
        btnTambah.style.display = 'none';
    });

    btnBatal.addEventListener('click', function() {
        formTambah.style.display = 'none';
        btnTambah.style.display = 'block';
        formTambah.querySelector('input[type="file"]').value = '';
    });

});
</script>
@endpush
