<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusPesananMail;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('user')->latest()->get();
        return view('backend.pesanan.index', compact('pesanan'));
    }

    /**
     * Halaman "Detail Pesanan" — mirip tampilan di modul.
     */
    public function detail($id)
    {
        $item = Pesanan::with(['user', 'details.produk'])->findOrFail($id);
        return view('backend.pesanan.detail', compact('item'));
    }

    /**
     * Halaman cetak pesanan (tampilan polos untuk di-print).
     */
    public function cetak($id)
    {
        $item = Pesanan::with(['user', 'details.produk'])->findOrFail($id);
        return view('backend.pesanan.cetak', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pesanan'    => 'required',
            'ekspedisi'         => 'nullable|string|max:100',
            'layanan_ekspedisi' => 'nullable|string|max:100',
            'no_resi'           => 'nullable|string|max:100',
            'alamat'            => 'nullable|string',
            'kode_pos'          => 'nullable|string|max:20',
        ]);

        $pesanan         = Pesanan::findOrFail($id);
        $pesanan->status = $request->status_pesanan;

        // Simpan data ekspedisi hanya jika status = dikirim
        if (strtolower($request->status_pesanan) === 'dikirim') {
            if ($request->filled('ekspedisi')) {
                $pesanan->ekspedisi = $request->ekspedisi;
            }
            if ($request->filled('layanan_ekspedisi')) {
                $pesanan->layanan_ekspedisi = $request->layanan_ekspedisi;
            }
            $pesanan->no_resi = $request->no_resi;
        }

        // Alamat & kode pos bisa dikoreksi dari halaman detail
        if ($request->filled('alamat')) {
            $pesanan->alamat = $request->alamat;
        }
        if ($request->filled('kode_pos')) {
            $pesanan->kode_pos = $request->kode_pos;
        }

        $pesanan->save();

        // Kirim email notifikasi setelah response dikirim ke browser (non-blocking)
        if ($pesanan->user && $pesanan->user->email) {
            $email = $pesanan->user->email;
            $mail  = new StatusPesananMail($pesanan);

            try {
                Mail::to($email)->send($mail);
            } catch (\Exception $e) {
                Log::error('[Pesanan] Gagal kirim email: ' . $e->getMessage());
            }
        }

        // Dipanggil lewat AJAX (modal index lama) → balikin JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui dan email notifikasi telah dikirim!',
            ]);
        }

        // Dipanggil lewat form biasa (halaman detail) → redirect kembali
        return redirect()
            ->route('admin.pesanan.detail', $pesanan->id)
            ->with('success', 'Status pesanan berhasil diperbarui dan email notifikasi telah dikirim!');
    }

    public function tracking($id)
    {
        $pesanan = Pesanan::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Gunakan API Binderbyte jika no_resi & ekspedisi tersedia
        if ($pesanan->no_resi && $pesanan->ekspedisi) {
            $apiKey = config('services.binderbyte.api_key');

            $resp = Http::get('https://api.binderbyte.com/v1/track', [
                'api_key' => $apiKey,
                'courier' => $pesanan->ekspedisi,
                'awb'     => $pesanan->no_resi,
            ]);

            if ($resp->ok()) {
                $bind    = $resp->json();
                $history = collect($bind['data']['history'] ?? [])->map(fn($h) => [
                    'status' => $h['desc']     ?? '-',
                    'lokasi' => $h['location'] ?? '-',
                    'waktu'  => $h['date']     ?? '',
                    'tipe'   => 'done',
                ])->toArray();

                if (!empty($history)) {
                    $history[0]['tipe'] = 'current';
                }

                return response()->json([
                    'ekspedisi'       => $pesanan->ekspedisi,
                    'layanan'         => $pesanan->layanan_ekspedisi,
                    'no_resi'         => $pesanan->no_resi,
                    'estimasi_tiba'   => $bind['data']['summary']['estimasi'] ?? '-',
                    'posisi_sekarang' => [
                        'nama'   => $history[0]['lokasi'] ?? '-',
                        'alamat' => '',
                    ],
                    'riwayat' => $history,
                ]);
            }
        }

        // Fallback: kembalikan data dasar dari database
        return response()->json([
            'ekspedisi'       => $pesanan->ekspedisi,
            'layanan'         => $pesanan->layanan_ekspedisi,
            'no_resi'         => $pesanan->no_resi,
            'estimasi_tiba'   => 'Hubungi ekspedisi untuk estimasi tiba',
            'posisi_sekarang' => [
                'nama'   => 'Paket sedang dalam pengiriman',
                'alamat' => 'Lacak posisi detail di website ekspedisi',
            ],
            'riwayat' => [
                [
                    'status' => 'Pesanan dikemas oleh penjual',
                    'lokasi' => 'Gudang Penjual',
                    'waktu'  => $pesanan->created_at
                                    ? $pesanan->created_at->format('d M Y, H:i') . ' WIB'
                                    : '',
                    'tipe'   => 'done',
                ],
                [
                    'status' => 'Paket diterima ' . strtoupper($pesanan->ekspedisi ?? 'ekspedisi'),
                    'lokasi' => '-',
                    'waktu'  => '',
                    'tipe'   => 'done',
                ],
                [
                    'status' => 'Paket dalam perjalanan pengiriman',
                    'lokasi' => '-',
                    'waktu'  => $pesanan->updated_at
                                    ? $pesanan->updated_at->format('d M Y, H:i') . ' WIB'
                                    : '',
                    'tipe'   => 'current',
                ],
                [
                    'status' => 'Paket tiba di tujuan',
                    'lokasi' => '-',
                    'waktu'  => '',
                    'tipe'   => 'pending',
                ],
            ],
        ]);
    }
}