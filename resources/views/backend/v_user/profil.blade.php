@extends('backend.v_layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body border-top">
                <h5 class="card-title">{{ $judul }}</h5>

                @if(session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('backend.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group row align-items-center mb-4">
                        <div class="col-sm-2 text-center">
                            @if($edit->foto && file_exists(public_path('storage/img-user/' . $edit->foto)))
                            <img src="{{ asset('storage/img-user/' . $edit->foto) }}" id="preview-foto"
                                alt="Foto Profil" class="rounded-circle img-thumbnail shadow-sm"
                                style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                            <img src="{{ asset('image/icon_univ_bsi.png') }}" id="preview-foto" alt="Foto Default"
                                class="rounded-circle img-thumbnail shadow-sm"
                                style="width: 120px; height: 120px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="col-sm-10">
                            <label class="font-weight-bold">Foto Profil Baru <small
                                    class="text-muted">(Opsional)</small></label>
                            <input type="file" name="foto" id="input-foto"
                                class="form-control-file @error('foto') is-invalid @enderror"
                                onchange="bacaGambar(this)">
                            <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG, GIF. Maksimal ukuran file
                                1MB.</small>
                            @error('foto') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $edit->nama) }}" required>
                        @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $edit->email) }}" required>
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">No. Handphone <span class="text-danger">*</span></label>
                        <input type="text" name="hp" class="form-control @error('hp') is-invalid @enderror"
                            value="{{ old('hp', $edit->hp) }}" onkeypress="return hanyaAngka(event)" required>
                        @error('hp') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" name="tempat_lahir"
                                    class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    value="{{ old('tempat_lahir', $edit->tempat_lahir) }}" placeholder="Contoh: Jakarta"
                                    required>
                                @error('tempat_lahir') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir"
                                    class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    value="{{ old('tanggal_lahir', $edit->tanggal_lahir) }}" required>
                                @error('tanggal_lahir') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $edit->alamat) }}</textarea>
                        @error('alamat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label class="font-weight-bold">Password Baru <small class="text-muted">(Kosongkan jika tidak
                                ingin mengubah password)</small></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn btn-primary shadow-sm">Simpan Perubahan</button>
                        @if(auth()->user()->role === '2')
                        <a href="{{ route('customer.produk.index') }}" class="btn btn-secondary shadow-sm">Kembali</a>
                        @else
                        <a href="{{ route('backend.user.index') }}" class="btn btn-secondary shadow-sm">Kembali</a>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
function bacaGambar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-foto').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
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