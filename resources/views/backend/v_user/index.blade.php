@extends('backend.v_layouts.app')

@section('judul', 'Data User')

@section('content')

<style>
/* isi sel rata tengah secara vertikal supaya foto, teks, dan tombol sejajar */
#zero_config td,
#zero_config th {
    vertical-align: middle;
}

/* tombol Ubah & Hapus sejajar ke samping dengan jarak, tidak turun ke bawah */
.aksi-user {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.aksi-user form {
    margin: 0;
}

.aksi-user .btn {
    min-width: 78px;
}
</style>

<div class="row">
    <div class="col-12">

        {{-- Hanya Super Admin yang bisa tambah user --}}
        @if (auth()->user()->isSuperAdmin())
        <a href="{{ route('backend.user.create') }}">
            <button type="button" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </a>
        @endif

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $judul }}</h5>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th data-orderable="false">Foto</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($index as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    @php
                                        $fotoUrl = asset('image/logo.png');
                                        if ($row->foto) {
                                            foreach (['img-customer', 'img-user'] as $folder) {
                                                if (file_exists(public_path('storage/' . $folder . '/' . $row->foto))) {
                                                    $fotoUrl = asset('storage/' . $folder . '/' . $row->foto);
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $fotoUrl }}" alt="{{ $row->nama }}"
                                        class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                </td>
                                <td>{{ $row->email }}</td>
                                <td class="text-nowrap">{{ $row->hp ?: '-' }}</td>
                                <td class="text-nowrap">{{ $row->nama }}</td>
                                <td class="text-nowrap">
                                    @if ($row->role == \App\Models\User::ROLE_SUPERADMIN)
                                    <span class="badge badge-success">Super Admin</span>
                                    @elseif ($row->role == \App\Models\User::ROLE_ADMIN)
                                    <span class="badge badge-primary">Admin</span>
                                    @elseif ($row->role == \App\Models\User::ROLE_CUSTOMER)
                                    <span class="badge badge-warning text-white">Customer</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    {{-- Semua akun selalu Aktif (opsi NonAktif sudah dihapus) --}}
                                    <span class="badge badge-success">Aktif</span>
                                </td>
                                <td>
                                    {{-- Hanya Super Admin yang bisa Ubah & Hapus --}}
                                    @if (auth()->user()->isSuperAdmin())
                                    <div class="aksi-user">
                                        <a href="{{ route('backend.user.edit', $row->id) }}" title="Ubah Data">
                                            <button type="button" class="btn btn-cyan btn-sm">
                                                <i class="far fa-edit"></i> Ubah
                                            </button>
                                        </a>
                                        <form method="POST" action="{{ route('backend.user.destroy', $row->id) }}"
                                            id="form-hapus-{{ $row->id }}">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                                data-id="{{ $row->id }}" data-nama="{{ $row->nama }}"
                                                title="Hapus Data">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                    <span class="badge badge-secondary">Hanya Lihat</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-hapus').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var nama = this.getAttribute('data-nama');

        Swal.fire({
            title: 'Yakin ingin hapus?',
            text: 'User "' + nama + '" akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-' + id).submit();
            }
        });
    });
});
</script>

@endsection