<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Halaman pembayaran (Step 2)
     * GET /customer/checkout/{id}/pembayaran
     */
    public function pembayaran($id)
    {
        // Cari pesanan berdasarkan ID, jika tidak ada langsung lempar ke halaman 404 aman
        $pesanan = Pesanan::findOrFail($id);

        // Pastikan pesanan milik user yang sedang login
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        // Ambil detail pesanan
        $details = $pesanan->detailPesanan;

        // Data Virtual Account (dummy)
        $vaNumbers = [
            'bca'     => '8277 ' . str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) . ' 3456 7890',
            'mandiri' => '8914 ' . str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) . ' 3456 7890',
            'bri'     => '8888 ' . str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) . ' 3456 7890',
            'bni'     => '8009 ' . str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) . ' 3456 7890',
        ];

        // Kode gerai (dummy)
        $geraiKode = [
            'alfamart'  => 'ALF-' . strtoupper(substr($pesanan->kode_pesanan, -8)),
            'indomaret' => 'IDM-' . strtoupper(substr($pesanan->kode_pesanan, -8)),
        ];

        // Batas waktu pembayaran: 12 jam dari pesanan dibuat
        $batasWaktu = $pesanan->created_at->addHours(12);
        $sisaDetik  = max(0, $batasWaktu->diffInSeconds(now(), false));

        return view('customer.checkout.pembayaran', compact(
            'pesanan', 'details', 'vaNumbers', 'geraiKode', 'sisaDetik'
        ));
    }

    /**
     * Simpan metode pembayaran yang dipilih
     * POST /customer/checkout/{id}/bayar
     */
    public function bayar(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'metode_pembayaran' => 'required|in:bca,mandiri,bri,bni,alfamart,indomaret',
        ]);

        // Gunakan fill + save agar tidak error kalau kolom belum ada di fillable
        $pesanan->metode_pembayaran = $request->metode_pembayaran;
        $pesanan->status            = 'Selesai Memilih Pembayaran';
        $pesanan->save();

        return redirect()->route('customer.checkout.selesai', $pesanan->id);
    }

    /**
     * Halaman selesai (Step 3)
     * GET /customer/checkout/{id}/selesai
     */
    public function selesai($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        // Pastikan mengambil data pesanan, lalu arahkan ke view selesai
        return view('customer.checkout.selesai', compact('pesanan'));
    }
}