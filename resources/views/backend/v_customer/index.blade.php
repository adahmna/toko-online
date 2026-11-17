@extends('backend.v_layouts.app')

@section('judul', $judul)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $sub }}</h5>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th width="20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($index as $i => $item)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $item->user->nama ?? '-' }}</td>
                                <td>{{ $item->user->email ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center" style="gap: 5px;">
                                        <a href="{{ route('customer.backend.customer.show', $item->id) }}"
                                            class="btn btn-warning btn-sm" title="Lihat Detail Customer">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('customer.backend.customer.edit', $item->id) }}"
                                            class="btn btn-info btn-sm" title="Ubah Customer">
                                            <i class="fas fa-pen"></i> Ubah
                                        </a>
                                        <form action="{{ route('customer.backend.customer.destroy', $item->id) }}"
                                            method="POST" onsubmit="return confirm('Yakin hapus customer ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Customer">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data customer.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
