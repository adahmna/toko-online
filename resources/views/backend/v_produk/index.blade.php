@extends('backend.v_layouts.app')
@section('content')
<!-- contentAwal -->

<div class="row">

    <div class="col-12">

        {{-- Tombol Tambah: hanya tampil untuk admin & superadmin --}}
        @if(auth()->user()->role !== '2') {{-- 2 = customer --}}
        <a href="{{ route('backend.produk.create') }}">
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
                                <th>Foto</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($index as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                {{-- Kolom Foto Produk --}}
                                <td class="text-center" style="padding: 8px;">
                                    @php
                                    $gambar = $row->gambar->first();
                                    @endphp

                                    @if($gambar)
                                    <div style="width:80px; height:80px; margin:auto; border-radius:8px; overflow:hidden; border:1px solid #e0e0e0; cursor:pointer;"
                                        onclick="previewFoto('{{ asset('storage/img-produk/' . $gambar->foto) }}', '{{ addslashes($row->nama_produk) }}')">
                                        <img src="{{ asset('storage/img-produk/' . $gambar->foto) }}"
                                            alt="{{ $row->nama_produk }}"
                                            style="width:100%; height:100%; object-fit:cover; display:block;"
                                            onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f1f3f5;color:#adb5bd;font-size:11px;text-align:center;\'><span><i class=\'fas fa-image\' style=\'font-size:18px;display:block;margin-bottom:3px;\'></i>Error</span></div>'">
                                    </div>
                                    @else
                                    <div style="width:80px; height:80px; background:#f1f3f5; border-radius:8px;
                                                border:1px solid #e0e0e0;
                                                display:flex; align-items:center; justify-content:center;
                                                margin:auto; color:#adb5bd; font-size:11px; text-align:center;">
                                        <span><i class="fas fa-image"
                                                style="font-size:18px; display:block; margin-bottom:3px;"></i>Belum
                                            ada</span>
                                    </div>
                                    @endif
                                </td>

                                <td>{{ $row->kategori->nama_kategori }}</td>

                                <td>
                                    @if ($row->status == 1)
                                    <span class="badge badge-success">
                                        <i></i> Publish
                                    </span>
                                    @elseif($row->status == 0)
                                    <span class="badge badge-secondary">
                                        <i></i> Blok
                                    </span>
                                    @endif
                                </td>

                                <td>{{ $row->nama_produk }}</td>

                                <td>
                                    Rp. {{ number_format($row->harga, 0, ',', '.') }}
                                </td>

                                <td>{{ $row->stok }}</td>

                                <td>
                                    <div style="display:flex; flex-wrap:wrap; gap:8px; align-items:center;">

                                        {{-- Tombol Ubah & Gambar: hanya untuk admin & superadmin --}}
                                        @if(auth()->user()->role !== '2')
                                        <a href="{{ route('backend.produk.edit', $row->id) }}" title="Ubah Data">
                                            <button type="button" class="btn btn-cyan btn-sm">
                                                <i class="far fa-edit"></i> Ubah
                                            </button>
                                        </a>

                                        <a href="{{ route('backend.produk.show', $row->id) }}" title="Gambar">
                                            <button type="button" class="btn btn-warning btn-sm">
                                                <i class="fas fa-plus"></i> Gambar
                                            </button>
                                        </a>
                                        @endif

                                        {{-- Tombol Keranjang: hanya untuk customer --}}
                                        @if(auth()->user()->role === '2') {{-- 2 = customer --}}
                                        <form method="POST" action="{{ route('customer.keranjang.store') }}"
                                            style="margin:0;">
                                            @csrf
                                            <input type="hidden" name="produk_id" value="{{ $row->id }}">
                                            <input type="hidden" name="jumlah" value="1">
                                            <input type="hidden" name="redirect" value="keranjang">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-shopping-cart"></i> Keranjang
                                            </button>
                                        </form>
                                        @endif

                                        {{-- Tombol Hapus: hanya untuk admin & superadmin --}}
                                        @if(auth()->user()->role !== '2') {{-- 2 = customer --}}
                                        <form method="POST" action="{{ route('backend.produk.destroy', $row->id) }}"
                                            id="form-hapus-{{ $row->id }}" style="margin:0;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                                data-id="{{ $row->id }}" data-nama="{{ $row->nama_produk }}">
                                                <i class="far fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                        @endif

                                    </div>
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

{{-- Modal Preview Foto --}}
<div id="modalFoto" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(0,0,0,0.7); z-index:9999; align-items:center; justify-content:center;" onclick="tutupModal()">
    <div style="position:relative; max-width:480px; width:90%;" onclick="event.stopPropagation()">
        <img id="modalImg" src="" alt="" style="width:100%; border-radius:10px; display:block;">
        <p id="modalNama" style="color:#fff; text-align:center; margin-top:10px; font-size:15px;"></p>
        <button onclick="tutupModal()" style="position:absolute; top:-12px; right:-12px; background:#fff; border:none;
                   border-radius:50%; width:30px; height:30px; font-size:16px; cursor:pointer; line-height:30px;">
            &times;
        </button>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Preview foto produk
function previewFoto(src, nama) {
    document.getElementById('modalImg').src = src;
    document.getElementById('modalNama').textContent = nama;
    var modal = document.getElementById('modalFoto');
    modal.style.display = 'flex';
}

function tutupModal() {
    document.getElementById('modalFoto').style.display = 'none';
}

// Hapus produk
document.querySelectorAll('.btn-hapus').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var nama = this.getAttribute('data-nama');

        Swal.fire({
            title: 'Yakin ingin hapus?',
            text: 'Produk "' + nama + '" akan dihapus permanen!',
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

<!-- contentAkhir -->
@endsection