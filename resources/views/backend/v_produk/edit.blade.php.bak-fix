@extends('backend.v_layouts.app')
@section('content')
<!-- contentAwal -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                {{-- ============================================================ --}}
                {{-- FORM UTAMA: Update Produk                                    --}}
                {{-- ============================================================ --}}
                <form action="{{ route('backend.produk.update', $edit->id) }}" method="post"
                    enctype="multipart/form-data">
                    @method('put')
                    @csrf

                    <div class="card-body">
                        <h4 class="card-title">{{ $judul }}</h4>

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="row">

                            {{-- ===== KOLOM KIRI: Foto ===== --}}
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label>Foto</label>
                                    @if ($edit->foto)
                                    <img src="{{ asset('storage/img-produk/' . $edit->foto) }}" class="foto-preview"
                                        width="100%">
                                    <p></p>
                                    @else
                                    <img src="{{ asset('storage/img-produk/imgdefault.jpg') }}" class="foto-preview"
                                        width="100%">
                                    <p></p>
                                    @endif
                                    <input type="file" name="foto"
                                        class="form-control @error('foto') is-invalid @enderror"
                                        onchange="previewFoto()">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
                                    @error('foto')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Ubah Foto --}}
                                <div class="form-group mt-4">
                                    <label>Klik Disini Untuk Menambah Foto</label>

                                    <div id="foto-container">
                                        @foreach($edit->gambar as $gambar)
                                        <div class="row mb-2 align-items-center" id="foto-row-{{ $gambar->id }}">
                                            <div class="col-md-8">
                                                <img src="{{ asset('storage/img-produk/' . $gambar->foto) }}"
                                                    width="100%" class="img-thumbnail">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="hapusFoto({{ $gambar->id }})">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <button type="button" class="btn btn-primary btn-sm mt-2" id="btn-tambah-foto">
                                        Tambah Foto
                                    </button>
                                </div>

                            </div>

                            {{-- ===== KOLOM KANAN: Detail Produk ===== --}}
                            <div class="col-md-8">

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="" {{ old('status', $edit->status) == '' ? 'selected' : '' }}>
                                            - Pilih Status -
                                        </option>
                                        <option value="1" {{ old('status', $edit->status) == '1' ? 'selected' : '' }}>
                                            Public
                                        </option>
                                        <option value="0" {{ old('status', $edit->status) == '0' ? 'selected' : '' }}>
                                            Blok
                                        </option>
                                    </select>
                                    @error('status')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="kategori_id"
                                        class="form-control @error('kategori_id') is-invalid @enderror">
                                        <option value="" selected>- Pilih Kategori -</option>
                                        @foreach ($kategori as $row)
                                        @if (old('kategori_id', $edit->kategori_id) == $row->id)
                                        <option value="{{ $row->id }}" selected>{{ $row->nama_kategori }}</option>
                                        @else
                                        <option value="{{ $row->id }}">{{ $row->nama_kategori }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama_produk"
                                        value="{{ old('nama_produk', $edit->nama_produk) }}"
                                        class="form-control @error('nama_produk') is-invalid @enderror"
                                        placeholder="Masukkan Nama Produk">
                                    @error('nama_produk')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Detail</label>
                                    <textarea name="detail" id="ckeditor"
                                        class="form-control @error('detail') is-invalid @enderror">{{ old('detail', $edit->detail) }}</textarea>
                                    @error('detail')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="text" name="harga" onkeypress="return hanyaAngka(event)"
                                        value="{{ old('harga', $edit->harga) }}"
                                        class="form-control @error('harga') is-invalid @enderror"
                                        placeholder="Masukkan Harga Produk">
                                    @error('harga')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Berat</label>
                                    <input type="text" name="berat" onkeypress="return hanyaAngka(event)"
                                        value="{{ old('berat', $edit->berat) }}"
                                        class="form-control @error('berat') is-invalid @enderror"
                                        placeholder="Masukkan Berat Produk">
                                    @error('berat')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Stok</label>
                                    <input type="text" name="stok" onkeypress="return hanyaAngka(event)"
                                        value="{{ old('stok', $edit->stok) }}"
                                        class="form-control @error('stok') is-invalid @enderror"
                                        placeholder="Masukkan Stok Produk">
                                    @error('stok')
                                    <span class="invalid-feedback alert-danger" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Perbaharui</button>
                            <a href="{{ route('backend.produk.index') }}">
                                <button type="button" class="btn btn-secondary">Kembali</button>
                            </a>
                        </div>
                    </div>

                </form>
                {{-- ============================================================ --}}
                {{-- AKHIR FORM UTAMA                                             --}}
                {{-- ============================================================ --}}

            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- FORM TAMBAH FOTO — di luar form utama agar tidak nested      --}}
{{-- ============================================================ --}}
<div id="form-tambah-foto" class="container-fluid mt-2" style="display:none;">
    <div class="card card-body border">
        <form id="form-foto-store" action="{{ route('backend.foto_produk.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="produk_id" value="{{ $edit->id }}">
            <div class="form-group">
                <label>Pilih Foto</label>
                <input type="file" name="foto_produk[]" class="form-control"
                    accept="image/jpeg,image/jpg,image/png,image/gif" required>
            </div>
            <button type="submit" class="btn btn-success btn-sm">Simpan</button>
            <button type="button" class="btn btn-secondary btn-sm" id="btn-batal">Batal</button>
        </form>
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
        formTambah.scrollIntoView({
            behavior: 'smooth'
        });
    });

    btnBatal.addEventListener('click', function() {
        formTambah.style.display = 'none';
        btnTambah.style.display = 'block';
        document.getElementById('form-foto-store').querySelector('input[type="file"]').value = '';
    });

});

function hapusFoto(id) {
    if (!confirm('Yakin ingin menghapus foto ini?')) return;

    fetch(`/foto-produk/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(res => {
            if (res.ok) {
                const row = document.getElementById('foto-row-' + id);
                if (row) row.remove();
            } else {
                alert('Gagal menghapus foto. Silakan coba lagi.');
            }
        })
        .catch(() => alert('Terjadi kesalahan jaringan.'));
}
</script>
@endpush
