@extends('backend.v_layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Produk</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('backend.laporan.cetakproduk') }}" method="POST" target="_blank">
                    @csrf

                    <div class="mb-3">
                        <label for="tanggal_awal" class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal"
                            class="form-control @error('tanggal_awal') is-invalid @enderror"
                            value="{{ old('tanggal_awal') }}">
                        @error('tanggal_awal')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                            class="form-control @error('tanggal_akhir') is-invalid @enderror"
                            value="{{ old('tanggal_akhir') }}">
                        @error('tanggal_akhir')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
