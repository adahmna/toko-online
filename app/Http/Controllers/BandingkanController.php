<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class BandingkanController extends Controller
{
    private const SESSION_KEY = 'bandingkan_produk';
    private const MAX_ITEM = 4;

    public function index()
    {
        $ids = session(self::SESSION_KEY, []);

        $produk = Produk::with(['kategori', 'gambar'])
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn ($item) => array_search($item->id, $ids))
            ->values();

        return view('v_bandingkan', compact('produk'));
    }

    public function store($produkId)
    {
        $ids = session(self::SESSION_KEY, []);

        if (in_array((int) $produkId, $ids)) {
            return back()->with('info', 'Produk sudah ada di daftar perbandingan.');
        }

        if (count($ids) >= self::MAX_ITEM) {
            return back()->with('error', 'Maksimal ' . self::MAX_ITEM . ' produk untuk dibandingkan.');
        }

        $ids[] = (int) $produkId;
        session([self::SESSION_KEY => $ids]);

        return back()->with('success', 'Produk ditambahkan ke perbandingan.');
    }

    public function destroy($produkId)
    {
        $ids = session(self::SESSION_KEY, []);
        $ids = array_values(array_diff($ids, [(int) $produkId]));
        session([self::SESSION_KEY => $ids]);

        return back()->with('success', 'Produk dihapus dari perbandingan.');
    }

    public function clear()
    {
        session()->forget(self::SESSION_KEY);
        return back()->with('success', 'Daftar perbandingan dikosongkan.');
    }
}