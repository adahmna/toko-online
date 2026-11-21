@extends('backend.v_layouts.app')
@section('content')
<div class="row">

    <div class="col-12">

        {{-- Tombol Tambah: Hanya tampil jika role BUKAN '2' (Customer) --}}
        @if(auth()->user()->role !== '2')
        <a href="{{ route('backend.kategori.create') }}">
            <button type="button" class="btn btn-primary">
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
                                <th>Nama Kategori</th>
                                {{-- Kolom Aksi Header: Hanya tampil jika bukan Customer --}}
                                @if(auth()->user()->role !== '2')
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($index as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nama_kategori }}</td>

                                {{-- Isi Kolom Aksi: Hanya tampil jika bukan Customer --}}
                                @if(auth()->user()->role !== '2')
                                <td>
                                    <div class="d-flex flex-wrap" style="gap: 6px;"><a href="{{ route('backend.kategori.edit', $row->id) }}" title="Ubah Data">
                                        <button type="button" class="btn btn-cyan btn-sm">
                                            <i class="far fa-edit"></i> Ubah
                                        </button>
                                    </a>

                                    <form method="POST" action="{{ route('backend.kategori.destroy', $row->id) }}"
                                        id="form-hapus-{{ $row->id }}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                            data-id="{{ $row->id }}" data-nama="{{ $row->nama_kategori }}">
                                            <i class="far fa-trash-alt"></i> Hapus
                                        </button>
                                    </form></div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Script SweetAlert: Hanya dimuat jika bukan Customer --}}
@if(auth()->user()->role !== '2')
<script>
document.querySelectorAll('.btn-hapus').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var nama = this.getAttribute('data-nama');

        Swal.fire({
            title: 'Yakin ingin hapus?',
            text: 'Kategori "' + nama + '" akan dihapus permanen!',
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
@endif

@endsection
