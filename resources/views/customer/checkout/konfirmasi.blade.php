{{-- resources/views/customer/checkout/konfirmasi.blade.php --}}

@extends('v_layouts.app')

@section('title', 'Konfirmasi Pesanan')

@section('content')
<div style="padding: 20px;">
    <h4 style="margin-bottom: 16px; font-weight: 600;">Konfirmasi Pesanan</h4>

    <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden; max-width: 820px;">
        {{-- Header --}}
        <div style="background: #4CAF50; color: white; padding: 13px 20px; font-size: 15px; font-weight: 600;">
            🧾 Konfirmasi Pesanan
        </div>

        {{-- Tabel Pesanan --}}
        <table class="table table-bordered mb-0" style="font-size: 14px;">
            <thead
                style="background: #f8f9fa; color: #6c757d; font-size: 12px; text-transform: uppercase; letter-spacing: .4px;">
                <tr>
                    <th style="width: 40px; padding: 10px 16px;">No</th>
                    <th style="padding: 10px 16px;">Nama Produk</th>
                    <th style="padding: 10px 16px;">Harga</th>
                    <th style="padding: 10px 16px;">Jumlah</th>
                    <th style="padding: 10px 16px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($keranjang as $i => $item)
                <tr>
                    <td style="padding: 12px 16px;">{{ $i + 1 }}</td>
                    <td style="padding: 12px 16px;">{{ $item->produk->nama_produk }}</td>
                    <td style="padding: 12px 16px;">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                    <td style="padding: 12px 16px;">{{ $item->jumlah }}</td>
                    <td style="padding: 12px 16px;">Rp
                        {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach

                {{-- Total --}}
                <tr style="background: #e8f5e9; font-weight: 700; color: #2e7d32;">
                    <td colspan="4" style="padding: 12px 16px;">TOTAL:</td>
                    <td style="padding: 12px 16px;">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Footer Tombol --}}
        <div
            style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid #dee2e6;">
            <a href="{{ route('customer.keranjang.index') }}"
                style="padding: 9px 18px; background: #607D8B; color: white; border-radius: 7px; font-size: 13px; font-weight: 600; text-decoration: none;">
                ← Kembali ke Keranjang
            </a>

            {{-- Form POST ke checkout store --}}
            <form action="{{ route('customer.checkout.store') }}" method="POST">
                @csrf
                <button type="submit"
                    style="padding: 9px 22px; background: #4CAF50; color: white; border: none; border-radius: 7px; font-size: 13px; font-weight: 700; cursor: pointer;">
                    Lanjut ke Pembayaran →
                </button>
            </form>
        </div>
    </div>
</div>
@endsection{{-- resources/views/customer/checkout/konfirmasi.blade.php --}}

@extends('layouts.customer') {{-- sesuaikan dengan layout kamu --}}

@section('title', 'Konfirmasi Pesanan')

@section('content')
<div style="padding: 20px;">
    <h4 style="margin-bottom: 16px; font-weight: 600;">Konfirmasi Pesanan</h4>

    <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden; max-width: 820px;">
        {{-- Header --}}
        <div style="background: #4CAF50; color: white; padding: 13px 20px; font-size: 15px; font-weight: 600;">
            🧾 Konfirmasi Pesanan
        </div>

        {{-- Tabel Pesanan --}}
        <table class="table table-bordered mb-0" style="font-size: 14px;">
            <thead
                style="background: #f8f9fa; color: #6c757d; font-size: 12px; text-transform: uppercase; letter-spacing: .4px;">
                <tr>
                    <th style="width: 40px; padding: 10px 16px;">No</th>
                    <th style="padding: 10px 16px;">Nama Produk</th>
                    <th style="padding: 10px 16px;">Harga</th>
                    <th style="padding: 10px 16px;">Jumlah</th>
                    <th style="padding: 10px 16px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($keranjang as $i => $item)
                <tr>
                    <td style="padding: 12px 16px;">{{ $i + 1 }}</td>
                    <td style="padding: 12px 16px;">{{ $item->produk->nama_produk }}</td>
                    <td style="padding: 12px 16px;">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                    <td style="padding: 12px 16px;">{{ $item->jumlah }}</td>
                    <td style="padding: 12px 16px;">Rp
                        {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach

                {{-- Total --}}
                <tr style="background: #e8f5e9; font-weight: 700; color: #2e7d32;">
                    <td colspan="4" style="padding: 12px 16px;">TOTAL:</td>
                    <td style="padding: 12px 16px;">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Footer Tombol --}}
        <div
            style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid #dee2e6;">
            <a href="{{ route('customer.keranjang.index') }}"
                style="padding: 9px 18px; background: #607D8B; color: white; border-radius: 7px; font-size: 13px; font-weight: 600; text-decoration: none;">
                ← Kembali ke Keranjang
            </a>

            {{-- Form POST ke checkout store --}}
            <form action="{{ route('customer.checkout.store') }}" method="POST">
                @csrf
                <button type="submit"
                    style="padding: 9px 22px; background: #4CAF50; color: white; border: none; border-radius: 7px; font-size: 13px; font-weight: 700; cursor: pointer;">
                    Lanjut ke Pembayaran →
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
