<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LamaranKerja;

class KarirController extends Controller
{
    public function index()
    {
        return view('frontend.v_karir.index', [
            'judul' => 'Karir - BSI Cyber Store',
        ]);
    }

    public function formLamar()
    {
        return view('frontend.v_karir.lamar', [
            'judul' => 'Lamar Kerja',
        ]);
    }

    public function prosesLamar(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'hp'     => 'required|numeric|digits_between:10,13',
            'alasan' => 'required|string|min:20',
            'cv'     => 'required|file|mimes:pdf|max:2048',
        ], [
            'alasan.min' => 'Alasan melamar minimal 20 karakter.',
            'cv.mimes'   => 'CV harus berformat PDF.',
            'cv.max'     => 'Ukuran CV maksimal 2MB.',
        ]);

        // Cek apakah email ini masih punya lamaran pending/diterima yang belum dipakai
        $existing = LamaranKerja::where('email', $request->email)
            ->whereIn('status', [LamaranKerja::STATUS_PENDING, LamaranKerja::STATUS_DITERIMA])
            ->first();

        if ($existing) {
            return back()->withErrors([
                'email' => $existing->status == LamaranKerja::STATUS_PENDING
                    ? 'Email ini masih punya lamaran yang sedang diproses.'
                    : 'Email ini sudah punya lamaran yang diterima. Silakan lanjut ke halaman registrasi admin.',
            ])->withInput();
        }

        $cvPath = $request->file('cv')->store('lamaran-cv', 'public');

        LamaranKerja::create([
            'nama'   => $request->nama,
            'email'  => $request->email,
            'hp'     => $request->hp,
            'alasan' => $request->alasan,
            'cv'     => $cvPath,
            'status' => LamaranKerja::STATUS_PENDING,
        ]);

        return redirect()->route('karir.index')
            ->with('success', 'Lamaran kamu berhasil dikirim! Tunggu review dari HRD.');
    }
}