<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use App\Models\Kategori;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function berandaBackend()
    {
        $user = auth()->user();

        // ===== STATISTIK UMUM =====
        $totalPesanan    = Pesanan::count();
        $totalPendapatan = Pesanan::where('status', 'selesai')->sum('total_harga');
        $totalProduk     = Produk::count();
        $totalCustomer   = User::where('role', '2')->count();

        // ===== PESANAN HARI INI =====
        $pesananHariIni = Pesanan::whereDate('created_at', Carbon::today())->count();

        // ===== PESANAN PENDING =====
        $pesananPending = Pesanan::where('status', 'menunggu pembayaran')->count();

        // ===== STOK MENIPIS COUNT (untuk kartu) =====
        $stokMenipis = Produk::where('stok', '<', 5)->count();

        // ===== STOK MENIPIS LIST (untuk tabel warning) =====
        $stokMenipisList = Produk::where('stok', '<', 5)
            ->with('kategori')
            ->orderBy('stok', 'asc')
            ->get();

        // ===== GRAFIK PESANAN 6 BULAN TERAKHIR (render pertama kali) =====
        [$grafikLabel, $grafikData] = $this->hitungGrafikPesanan();

        // ===== PESANAN TERBARU =====
        $pesananTerbaru = Pesanan::with('user')->latest()->take(5)->get();

        // ===== PRODUK TERLARIS =====
        $produkTerlaris = Produk::withCount('pesananDetail')
            ->orderBy('pesanan_detail_count', 'desc')
            ->take(5)
            ->get();

        return view('backend.v_beranda.index', compact(
            'totalPesanan',
            'totalPendapatan',
            'totalProduk',
            'totalCustomer',
            'pesananHariIni',
            'pesananPending',
            'stokMenipis',
            'stokMenipisList',
            'grafikData',
            'grafikLabel',
            'pesananTerbaru',
            'produkTerlaris',
        ));
    }

    /**
     * Endpoint AJAX untuk update grafik pesanan secara berkala (real-time polling).
     * Dipanggil dari JS dashboard tiap beberapa detik.
     */
    public function grafikPesananAjax()
    {
        [$grafikLabel, $grafikData] = $this->hitungGrafikPesanan();

        return response()->json([
            'labels' => $grafikLabel,
            'data'   => $grafikData,
            'jam'    => Carbon::now()->format('H:i:s'),
        ]);
    }

    /**
     * Hitung ulang data grafik pesanan 6 bulan terakhir.
     * Dipakai bareng oleh berandaBackend() dan grafikPesananAjax()
     * supaya query-nya selalu konsisten dan datanya selalu yang terbaru.
     */
    private function hitungGrafikPesanan(): array
    {
        $grafikData  = [];
        $grafikLabel = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan         = Carbon::now()->subMonths($i);
            $grafikLabel[] = $bulan->translatedFormat('M Y');
            $grafikData[]  = Pesanan::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }

        return [$grafikLabel, $grafikData];
    }

    public function index()
    {
        $produk = Produk::where('status', 1)->orderBy('updated_at', 'desc')->paginate(6);
        $kategoris = Kategori::all();
        $produkBanner1 = Produk::whereHas('kategori', fn($q) => $q->where('nama_kategori', 'like', '%Tas%'))->first() ?? Produk::first();
        $produkBanner2 = Produk::whereHas('kategori', fn($q) => $q->where('nama_kategori', 'like', '%Pakaian%'))->first() ?? Produk::first();
        $produkBanner3 = Produk::orderBy('created_at', 'desc')->first();
        return view('v_beranda.index', [
            'judul' => 'Halaman Beranda',
            'produk' => $produk,
            'kategoris' => $kategoris,
            'produkBanner1' => $produkBanner1,
            'produkBanner2' => $produkBanner2,
            'produkBanner3' => $produkBanner3,
        ]);
    }
}