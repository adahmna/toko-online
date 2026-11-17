@extends('backend.v_layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('backend.user.update', $edit->id) }}" method="post"
                    enctype="multipart/form-data">
                    @method('put')
                    @csrf

                    <div class="card-body">
                        <h4 class="card-title">{{ $judul }}</h4>
                        <div class="row">

                            {{-- Kolom Kiri: Foto Profil Opsional --}}
                            <div class="col-md-4">
                                <div class="form-group text-center">
                                    <label class="d-block text-left">Foto Profil <span
                                            class="text-muted">(Opsional)</span></label>

                                    {{-- Pratinjau Foto --}}
                                    <div class="mb-3">
                                        @if ($edit->foto && file_exists(public_path('storage/img-user/' . $edit->foto)))
                                        <img src="{{ asset('storage/img-user/' . $edit->foto) }}"
                                            class="rounded foto-preview" width="100%"
                                            style="max-width: 180px; height: 180px; object-fit: cover;">
                                        @else
                                        <img src="{{ asset('image/logo.png') }}" class="rounded foto-preview"
                                            width="100%" style="max-width: 180px; height: 180px; object-fit: cover;">
                                        @endif
                                    </div>

                                    {{-- Input File --}}
                                    <input type="file" name="foto"
                                        class="form-control @error('foto') is-invalid @enderror"
                                        onchange="previewFoto()">

                                    @error('foto')
                                    <div class="invalid-feedback text-left alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Kolom Kanan: Data Informasi User --}}
                            <div class="col-md-8">
                                {{-- Hak Akses (nilai diambil dari konstanta model User supaya tidak tertukar) --}}
                                <div class="form-group">
                                    <label>Hak Akses <span class="text-danger">*</span></label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror"
                                        required>
                                        <option value="">- Pilih Hak Akses -</option>
                                        <option value="{{ \App\Models\User::ROLE_SUPERADMIN }}"
                                            {{ (string) old('role', $edit->role) === \App\Models\User::ROLE_SUPERADMIN ? 'selected' : '' }}>
                                            Super Admin</option>
                                        <option value="{{ \App\Models\User::ROLE_ADMIN }}"
                                            {{ (string) old('role', $edit->role) === \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>
                                            Admin</option>
                                        <option value="{{ \App\Models\User::ROLE_CUSTOMER }}"
                                            {{ (string) old('role', $edit->role) === \App\Models\User::ROLE_CUSTOMER ? 'selected' : '' }}>
                                            Customer</option>
                                    </select>
                                    @error('role')
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Nama --}}
                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" value="{{ old('nama', $edit->nama) }}"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        placeholder="Masukkan Nama" required>
                                    @error('nama')
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $edit->email) }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Masukkan Email" required>
                                    @error('email')
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Nomor HP --}}
                                <div class="form-group">
                                    <label>HP <span class="text-danger">*</span></label>
                                    <input type="text" name="hp" value="{{ old('hp', $edit->hp) }}"
                                        class="form-control @error('hp') is-invalid @enderror"
                                        onkeypress="return hanyaAngka(event)" placeholder="Masukkan Nomor HP" required>
                                    @error('hp')
                                    <span class="invalid-feedback alert-danger" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Perbaharui</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk Preview Realtime --}}
<script>
function previewFoto() {
    const foto = document.querySelector('input[name="foto"]');
    const imgPreview = document.querySelector('.foto-preview');

    if (foto.files && foto.files[0]) {
        const fileFoto = new FileReader();
        fileFoto.readAsDataURL(foto.files[0]);

        fileFoto.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
}

function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>

@endsection