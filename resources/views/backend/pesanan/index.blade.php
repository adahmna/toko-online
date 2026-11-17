@extends('backend.v_layouts.app')

@section('judul', 'Manajemen Transaksi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pesanan</h5>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th class="text-center">ID Order</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                                <th>Pelanggan</th>
                                <th width="18%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $item->id }}</td>
                                <td class="text-center">{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>

                                <td class="text-center">
                                    @if(strtolower($item->status) == 'menunggu pembayaran')
                                    <span class="badge badge-warning text-dark">Menunggu Pembayaran</span>
                                    @elseif(strtolower($item->status) == 'proses' || strtolower($item->status) ==
                                    'diproses')
                                    <span class="badge badge-primary">Proses</span>
                                    @elseif(strtolower($item->status) == 'dikirim')
                                    <span class="badge badge-info">Dikirim</span>
                                    @elseif(strtolower($item->status) == 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                    @else
                                    <span class="badge badge-danger">{{ $item->status ?? 'Batal' }}</span>
                                    @endif
                                </td>

                                <td>{{ $item->user->email ?? ($item->user->nama ?? 'Guest') }}</td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center" style="gap: 5px;">
                                        <a href="{{ route('admin.pesanan.detail', $item->id) }}"
                                            class="btn btn-primary btn-sm" title="Lihat Detail Pesanan">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('admin.pesanan.cetak', $item->id) }}" target="_blank"
                                            class="btn btn-secondary btn-sm" title="Cetak Pesanan">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data transaksi masuk.</td>
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
