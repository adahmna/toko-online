<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FotoProduk;

class FotoProdukController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'foto_produk' => 'required|image|mimes:jpeg,jpg,png,gif|max:1024',
            'produk_id'   => 'required|exists:produk,id',
        ]);

        if ($request->file('foto_produk')) {
            $file = $request->file('foto_produk');
            $extension = $file->getClientOriginalExtension();
            $fileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;

            $file->move(public_path('storage/img-produk/'), $fileName);

            FotoProduk::create([
                'produk_id' => $request->produk_id,
                'foto'      => $fileName,
            ]);
        }

        return redirect()->back()->with('success', 'Foto berhasil ditambahkan');
    }

    public function destroy(string $id)
    {
        $foto = FotoProduk::findOrFail($id);
        $fotoPath = public_path('storage/img-produk/' . $foto->foto);

        if ($foto->foto && file_exists($fotoPath)) {
            unlink($fotoPath);
        }

        $foto->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus');
    }
}