<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        
        // Kuncinya di sini: Kita arahkan SEMUA role ke satu view backend yang sama
        return view('backend.v_kategori.index', [
            'judul' => 'Kategori',
            'index' => $kategori
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.v_kategori.create', [
            'judul' => 'Kategori',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|max:255|unique:kategori',
        ]);
        Kategori::create($validatedData);
        return redirect()->route('backend.kategori.index')
            ->with('success', 'Data berhasil tersimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = Kategori::find($id);
        return view('backend.v_kategori.edit', [
            'judul' => 'Kategori',
            'edit'  => $kategori
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'nama_kategori' => 'required|max:255|unique:kategori,nama_kategori,' . $id,
        ];
        $validatedData = $request->validate($rules);
        Kategori::where('id', $id)->update($validatedData);
        return redirect()->route('backend.kategori.index')
            ->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Blokir hapus kategori kalau masih ada produk yang stoknya tersedia
        $produkBerstok = \App\Models\Produk::where('kategori_id', $id)->where('stok', '>', 0)->get(['nama_produk', 'stok']);
        if ($produkBerstok->isNotEmpty()) {
            $pesan = 'Kategori tidak bisa dihapus karena masih ada produk dengan stok: '
                . $produkBerstok->map(fn ($x) => $x->nama_produk . ' (' . $x->stok . ')')->implode(', ')
                . '. Kosongkan stok produk itu dulu.';
            return redirect()->back()->with('error', $pesan)->with('msgError', $pesan);
        }
        $kategori = Kategori::findOrFail($id);

        if ($kategori->produk()->count() > 0) {
            return redirect()->route('backend.kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk!');
        }

        $kategori->delete();
        return redirect()->route('backend.kategori.index')
            ->with('success', 'Data berhasil dihapus');
    }
}