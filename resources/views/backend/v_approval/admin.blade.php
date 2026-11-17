@extends('backend.v_layouts.app')

@section('judul', 'Approval Akun Admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Akun Admin Menunggu Persetujuan</h5>
    </div>
    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($akunPending->isEmpty())
        <p class="text-muted mb-0">Tidak ada pendaftaran akun admin yang menunggu persetujuan.</p>
        @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Terdaftar</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akunPending as $user)
                    <tr>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <form action="{{ route('backend.approval.admin.approve', $user->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success"
                                    onclick="return confirm('Setujui akun admin {{ $user->nama }}?')">
                                    Setujui
                                </button>
                            </form>

                            <form action="{{ route('backend.approval.admin.reject', $user->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Tolak & hapus pendaftaran {{ $user->nama }}? Tindakan ini tidak bisa dibatalkan.')">
                                    Tolak
                                </button>
                            </form>
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