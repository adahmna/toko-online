@extends('v_layouts.app')

@section('content')
<style>
.ppf-wrapper {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    display: block;
    width: 100%;
}

.ppf-img {
    display: block;
    width: 100%;
    transition: filter .25s ease;
}

.ppf-overlay {
    position: absolute;
    inset: 0;
    background: rgba(20, 25, 35, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    opacity: 0;
    transform: translateY(8px);
    transition: opacity .25s ease, transform .25s ease;
    pointer-events: none;
}

.ppf-wrapper:hover .ppf-overlay {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.ppf-wrapper:hover .ppf-img {
    filter: brightness(.85);
}

.ppf-btn {
    width: 42px !important;
    height: 42px !important;
    border-radius: 50% !important;
    background: #fff;
    color: #1E2A3A;
    display: flex !important;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .25);
    transition: transform .2s ease, background .2s ease, color .2s ease;
    transform: scale(.8);
    margin: 0;
}

.ppf-wrapper:hover .ppf-btn {
    transform: scale(1);
}

.ppf-btn-edit:hover {
    background: #1976D2;
    color: #fff;
    transform: scale(1.12) !important;
}

.ppf-btn-delete:hover {
    background: #E53935;
    color: #fff;
    transform: scale(1.12) !important;
}

.ppf-filename {
    font-size: 12.5px;
    color: #5B6472;
    margin-top: 6px;
}

/* ===== AKUN CUSTOMER - MENYESUAIKAN TEMA TOKO ===== */
.akun-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 6px;
    padding: 30px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
}

.akun-label {
    display: block;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #1E2A3A;
    margin-bottom: 8px;
}

.akun-card .form-group {
    margin-bottom: 20px;
}

.akun-card .input,
.akun-card textarea.input {
    width: 100%;
    height: 45px;
    border: 1px solid #e8e8e8;
    border-radius: 25px;
    padding: 0 20px;
    font-size: 14px;
    color: #5B6472;
    background: #fff;
    transition: border-color .2s ease;
}

.akun-card textarea.input {
    height: auto;
    border-radius: 12px;
    padding: 12px 20px;
    resize: vertical;
}

.akun-card .input:focus,
.akun-card textarea.input:focus {
    outline: none;
    border-color: #F2711C;
}

.akun-card .input.is-invalid,
.akun-card textarea.input.is-invalid {
    border-color: #E53935;
}

.akun-card .invalid-feedback {
    color: #E53935;
    font-size: 12.5px;
    margin-top: 6px;
}

.akun-title {
    font-size: 20px;
    font-weight: 700;
    text-transform: uppercase;
    color: #1E2A3A;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
</style>

<div class="akun-card">
    <h4 class="akun-title">{{ $subJudul ?? $judul }}</h4>

    {{-- Alert Notification --}}
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session()->has('msgError'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('msgError') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <form action="{{ route('customer.akun.update', $edit->user_id) }}" method="POST" enctype="multipart/form-data">
        @method('put')
        @csrf

        <div class="row">
            {{-- Column Foto --}}
            <div class="col-md-4 mb-3">
                <label class="akun-label">Foto Profil</label>

                <div class="ppf-wrapper mb-2">
                    <img id="ppf-preview"
                        src="{{ $edit->user->foto ? asset('storage/img-customer/' . $edit->user->foto) : asset('storage/img-customer/default.jpg') }}"
                        class="img-thumbnail ppf-img" alt="Foto Profil">

                    <div class="ppf-overlay">
                        <label for="ppf-file-input" class="ppf-btn ppf-btn-edit" title="Ganti foto">
                            <i class="fa fa-pencil"></i>
                        </label>
                        <button type="button" class="ppf-btn ppf-btn-delete" id="ppf-delete-btn" title="Hapus foto"
                            style="{{ $edit->user->foto ? '' : 'display:none;' }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>

                <input type="file" name="foto" id="ppf-file-input" class="@error('foto') is-invalid @enderror"
                    style="display:none;">
                <input type="hidden" name="hapus_foto" id="ppf-hapus-foto" value="0">

                <div id="ppf-filename" class="ppf-filename"></div>

                @error('foto')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            {{-- Column Input Fields --}}
            <div class="col-md-6">
                {{-- Nama --}}
                <div class="form-group">
                    <label for="nama" class="akun-label">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="input @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $edit->user->nama) }}">
                    @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="akun-label">Email</label>
                    <input type="email" name="email" id="email" class="input @error('email') is-invalid @enderror"
                        value="{{ old('email', $edit->user->email) }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- No HP --}}
                <div class="form-group">
                    <label for="hp" class="akun-label">No. Handphone</label>
                    <input type="text" name="hp" id="hp" class="input @error('hp') is-invalid @enderror"
                        value="{{ old('hp', $edit->user->hp) }}">
                    @error('hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label for="alamat" class="akun-label">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="input @error('alamat') is-invalid @enderror">{{ old('alamat', $edit->alamat) }}</textarea>
                    @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kode Pos --}}
                <div class="form-group">
                    <label for="pos" class="akun-label">Kode Pos</label>
                    <input type="text" name="pos" id="pos" class="input @error('pos') is-invalid @enderror"
                        value="{{ old('pos', $edit->pos) }}">
                    @error('pos')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="primary-btn">Simpan</button>
                <a href="{{ route('beranda') }}" class="primary-btn" style="margin-left:10px;">Kembali</a>
            </div>
        </div>
    </form>
</div>

<script>
(function() {
    const fileInput = document.getElementById('ppf-file-input');
    const preview = document.getElementById('ppf-preview');
    const hapusInput = document.getElementById('ppf-hapus-foto');
    const deleteBtn = document.getElementById('ppf-delete-btn');
    const filenameEl = document.getElementById('ppf-filename');
    const defaultSrc = "{{ asset('storage/img-customer/default.jpg') }}";

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            hapusInput.value = '0';
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
            filenameEl.textContent = this.files[0].name;
            deleteBtn.style.display = 'flex';
        }
    });

    deleteBtn.addEventListener('click', function() {
        if (!confirm('Hapus foto profil dan kembalikan ke foto default?')) return;
        fileInput.value = '';
        hapusInput.value = '1';
        preview.src = defaultSrc;
        filenameEl.textContent = '';
        deleteBtn.style.display = 'none';
    });
})();
</script>
@endsection