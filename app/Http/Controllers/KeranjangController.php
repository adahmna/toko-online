<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\StokMenipisMail;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::with('produk')
            ->where('user_id', Auth::id())
            ->get();

        return view('keranjang.index', compact('keranjangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $produk    = Produk::findOrFail($request->produk_id);
        $keranjang = Keranjang::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($keranjang) {
            $keranjang->update([
                'jumlah' => $keranjang->jumlah + $request->jumlah
            ]);
        } else {
            Keranjang::create([
                'user_id'   => Auth::id(),
                'produk_id' => $request->produk_id,
                'jumlah'    => $request->jumlah,
                'harga'     => $produk->harga,
            ]);
        }

        if ($request->input('redirect') === 'keranjang') {
            return redirect()
                ->route('customer.keranjang.index')
                ->with('success', 'Produk "' . $produk->nama_produk . '" berhasil ditambahkan ke keranjang!');
        }

        return redirect()->back()
            ->with('success', 'Produk "' . $produk->nama_produk . '" berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $keranjang = Keranjang::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $keranjang->update(['jumlah' => $request->jumlah]);

        return redirect()->back()->with('success', 'Keranjang berhasil diupdate!');
    }

    public function destroy($id)
    {
        $keranjang = Keranjang::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $keranjang->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function clear()
    {
        Keranjang::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function count()
    {
        $count = Keranjang::where('user_id', Auth::id())->sum('jumlah');

        return response()->json(['count' => $count]);
    }

    public function checkout()
    {
        $keranjangs = Keranjang::with('produk')
            ->where('user_id', Auth::id())
            ->get();

        if ($keranjangs->isEmpty()) {
            return redirect()
                ->route('customer.keranjang.index')
                ->with('error', 'Keranjang masih kosong!');
        }

        $total = $keranjangs->sum('subtotal');

        return view('keranjang.checkout', compact('keranjangs', 'total'));
    }

    public function prosesCheckout(Request $request)
    {
        $keranjangs = Keranjang::with('produk')
            ->where('user_id', Auth::id())
            ->get();

        if ($keranjangs->isEmpty()) {
            return redirect()
                ->route('customer.keranjang.index')
                ->with('error', 'Keranjang masih kosong!');
        }

        // Cek stok semua produk
        foreach ($keranjangs as $item) {
            if ($item->produk->stok < $item->jumlah) {
                return redirect()
                    ->route('customer.keranjang.index')
                    ->with('error', 'Stok produk "' . $item->produk->nama_produk . '" tidak mencukupi! Sisa stok: ' . $item->produk->stok);
            }
        }

        $total = $keranjangs->sum('subtotal');

        // Simpan ke tabel pesanan
        $pesanan = Pesanan::create([
            'user_id'      => Auth::id(),
            'kode_pesanan' => 'ORD-' . strtoupper(uniqid()),
            'total_harga'  => $total,
            'status'       => 'Menunggu Pembayaran',
        ]);

        // Simpan detail pesanan dan kurangi stok
        foreach ($keranjangs as $item) {
            PesananDetail::create([
                'pesanan_id'  => $pesanan->id,
                'produk_id'   => $item->produk_id,
                'nama_produk' => $item->produk->nama_produk,
                'harga'       => $item->harga,
                'jumlah'      => $item->jumlah,
                'subtotal'    => $item->subtotal,
            ]);

            $item->produk->decrement('stok', $item->jumlah);
        }

        // Kosongkan keranjang
        Keranjang::where('user_id', Auth::id())->delete();

        // Cek stok menipis dan kirim email ke admin
        $produkMenipis = Produk::where('stok', '<', 5)->with('kategori')->get();
        if ($produkMenipis->isNotEmpty()) {
            $admins = User::whereIn('role', ['0', '1'])->get();
            foreach ($admins as $admin) {
                try {
                    Mail::to($admin->email)->send(new StokMenipisMail($produkMenipis));
                } catch (\Exception $e) {
                    // Gagal kirim email tidak mengganggu proses checkout
                }
            }
        }

        // ← Redirect ke halaman pembayaran (BUKAN riwayat)
        return redirect()->route('customer.checkout.pembayaran', $pesanan->id);
    }

    public function riwayatBelanja()
    {
        $pesanan = Pesanan::with('details')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.riwayat', compact('pesanan'));
    }
}