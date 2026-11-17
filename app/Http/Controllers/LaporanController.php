<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produk;
use App\Models\PesananDetail;

class LaporanController extends Controller
{
    public function formProduk()
    {
        return view('backend.laporan.formproduk');
    }

    public function cetakProduk(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal  = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $cetak = PesananDetail::with('produk')
            ->whereBetween('created_at', [
                $tanggalAwal  . ' 00:00:00',
                $tanggalAkhir . ' 23:59:59',
            ])
            ->get();

        $judul = 'Laporan Data Produk Terjual';

        return view('backend.laporan.cetak', compact('cetak', 'tanggalAwal', 'tanggalAkhir', 'judul'));
    }

    public function formUser()
    {
        return view('backend.laporan.formuser');
    }

    public function cetakUser(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $dari   = $request->tanggal_awal;
        $sampai = $request->tanggal_akhir;

        $users = User::whereBetween('created_at', [
            $dari   . ' 00:00:00',
            $sampai . ' 23:59:59',
        ])->get();

        return view('backend.laporan.cetakuser', compact('users', 'dari', 'sampai'));
    }
}