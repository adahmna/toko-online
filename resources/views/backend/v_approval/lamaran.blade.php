@extends('backend.v_layouts.app')

@section('judul', 'Approval Lamaran Kerja')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lamaran Kerja Menunggu Persetujuan</h5>
    </div>
    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($lamaran->isEmpty())
        <p class="text-muted mb-0">Tidak ada lamaran yang menunggu persetujuan.</p>
        @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Alasan Melamar</th>
                        <th>CV</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lamaran as $item)
                    <tr>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->hp }}</td>
                        <td style="max-width:300px; white-space:normal;">{{ $item->alasan }}</td>
                        <td>
                            @if($item->cv)
                            <a href="{{ asset('storage/' . $item->cv) }}" target="_blank"
                                class="btn btn-sm btn-outline-secondary">
                                Lihat CV
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('backend.approval.lamaran.approve', $item->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success"
                                    onclick="return confirm('Terima lamaran {{ $item->nama }}?')">
                                    Terima
                                </button>
                            </form>

                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                data-target="#tolakModal{{ $item->id }}">
                                Tolak
                            </button>

                            <!-- Modal alasan penolakan -->
                            <div class="modal fade" id="tolakModal{{ $item->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('backend.approval.lamaran.reject', $item->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h6 class="modal-title">Tolak Lamaran - {{ $item->nama }}</h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label">Catatan Admin (opsional)</label>
                                                <textarea name="catatan" class="form-control" rows="3"
                                                    placeholder="Alasan penolakan..."></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak Lamaran</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</div>
@endsection