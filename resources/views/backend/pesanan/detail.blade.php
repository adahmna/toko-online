@extends('backend.v_layouts.app')

@section('judul', 'Detail Pesanan')

@section('content')
<style>
.detail-pesanan-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    padding: 24px;
    max-width: 950px;
    margin: 0 auto;
}

.detail-header {
    text-align: center;
    margin-bottom: 24px;
}

.detail-header h3 {
    font-weight: 700;
    margin-bottom: 4px;
}

.detail-header .tanggal {
    color: #888;
    font-size: 13px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid #eee;
}

.info-block h6 {
    font-weight: 700;
    color: #444;
    margin-bottom: 8px;
    font-size: 13px;
    text-transform: uppercase;
}

.info-block p {
    margin: 0;
    font-size: 13px;
    color: #555;
    line-height: 1.6;
}

.produk-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 18px;
    font-size: 13px;
}

.produk-table thead th {
    background: #f5f7fa;
    padding: 8px 10px;
    text-align: left;
    font-weight: 600;
    color: #444;
    border-bottom: 2px solid #e8eaf0;
}

.produk-table tbody td {
    padding: 10px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

.produk-thumb {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #eee;
}

.produk-meta {
    font-size: 11px;
    color: #888;
    margin: 0;
}

.ringkasan-table {
    width: 100%;
    margin-bottom: 24px;
    font-size: 13px;
}

.ringkasan-table td {
    padding: 5px 10px;
    text-align: right;
}

.ringkasan-table tr.total-row td {
    font-weight: 700;
    font-size: 15px;
    border-top: 1px solid #ddd;
    padding-top: 10px;
}

.form-section {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
}

.form-section>div {
    flex: 1;
    min-width: 260px;
}

.form-group label {
    font-weight: 600;
    font-size: 13px;
    color: #444;
}

.action-buttons {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}
</style>

<div class="detail-pesanan-card">

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="detail-header">
        <h3>Detail Pesanan #{{ $item->id }}</h3>
        <div class="tanggal">Tanggal: {{ $item->created_at->format('d M Y H:i') }}</div>
    </div>

    <div class="info-row">
        <div class="info-block">
            <h6>Pelanggan</h6>
            <p>
                Nama: {{ $item->user->nama ?? '-' }}<br>
                Email: {{ $item->user->email ?? '-' }}<br>
                Hp: {{ $item->user->hp ?? '-' }}
            </p>
        </div>
        <div class="info-block text-right">
            <h6>Ongkos Kirim</h6>
            <p>
                Kurir: {{ $item->ekspedisi ?? '-' }}<br>
                Layanan: {{ $item->layanan_ekspedisi ?? '-' }}<br>
                Tujuan: {{ $item->kota_tujuan ?? '-' }}
            </p>
        </div>
    </div>

    <h6 style="font-weight:700; color:#444; font-size:13px; text-transform:uppercase; margin-bottom:10px;">Produk</h6>
    <table class="produk-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-right">Harga</th>
                <th class="text-center">Quantity</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($item->details as $d)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        @if($d->produk && $d->produk->foto)
                        <img src="{{ asset('storage/img-produk/thumb_sm_' . $d->produk->foto) }}" class="produk-thumb"
                            alt="">
                        @endif
                        <div>
                            <div>{{ $d->nama_produk ?? ($d->produk->nama_produk ?? '-') }}</div>
                            @if($d->produk)
                            <p class="produk-meta">Berat: {{ $d->produk->berat }} Gram</p>
                            <p class="produk-meta">Stok: {{ $d->produk->stok }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="text-right">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ $d->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
    $subtotal = $item->details->sum('subtotal');
    @endphp
    <table class="ringkasan-table">
        <tr>
            <td>Subtotal</td>
            <td width="160">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Ongkos Kirim</td>
            <td>Rp {{ number_format($item->ongkir, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL BAYAR</td>
            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <form action="{{ route('admin.pesanan.update', $item->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="ekspedisi" value="{{ $item->ekspedisi }}">
        <input type="hidden" name="layanan_ekspedisi" value="{{ $item->layanan_ekspedisi }}">

        <div class="form-section">
            <div>
                <div class="form-group">
                    <label>No. Resi</label>
                    <input type="text" name="no_resi" class="form-control" value="{{ $item->no_resi }}"
                        placeholder="Masukkan Nomor Resi">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status_pesanan" class="form-control">
                        <option value="Menunggu Pembayaran"
                            {{ $item->status=='Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="Proses" {{ $item->status=='Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Dikirim" {{ $item->status=='Dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="Selesai" {{ $item->status=='Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ $item->status=='Dibatalkan' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="4">{{ $item->alamat }}</textarea>
                </div>

                <div class="form-group">
                    <label>Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control" value="{{ $item->kode_pos }}">
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>

</div>
@endsection
