<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Wishlist::with('produk.kategori', 'produk.gambar')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('v_wishlist', compact('items'));
    }

    public function store(Request $request, $produkId)
    {
        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'produk_id' => $produkId,
        ]);

        return back()->with('success', 'Produk ditambahkan ke wishlist.');
    }

    public function destroy($id)
    {
        Wishlist::where('id', $id)->where('user_id', auth()->id())->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
