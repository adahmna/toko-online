<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LamaranKerja;
use App\Models\User;
use App\Mail\LamaranDiterimaMail;
use App\Mail\LamaranDitolakMail;
use Illuminate\Support\Facades\Mail;

class AdminApprovalController extends Controller
{
    // ===== LAMARAN KERJA =====
    public function lamaranIndex()
    {
        $lamaran = LamaranKerja::where('status', LamaranKerja::STATUS_PENDING)
            ->latest()->get();

        return view('backend.v_approval.lamaran', compact('lamaran'));
    }

    public function lamaranApprove($id)
    {
        $lamaran = LamaranKerja::findOrFail($id);
        $lamaran->update(['status' => LamaranKerja::STATUS_DITERIMA]);

        try {
            Mail::to($lamaran->email)->send(new LamaranDiterimaMail($lamaran));
        } catch (\Exception $e) {
            // Status tetap berhasil diubah walau email gagal terkirim
            report($e);
        }

        return back()->with('success', 'Lamaran ' . $lamaran->nama . ' diterima. Email pemberitahuan sudah dikirim.');
    }

    public function lamaranReject(Request $request, $id)
    {
        $lamaran = LamaranKerja::findOrFail($id);
        $lamaran->update([
            'status'        => LamaranKerja::STATUS_DITOLAK,
            'catatan_admin' => $request->input('catatan'),
        ]);

        try {
            Mail::to($lamaran->email)->send(new LamaranDitolakMail($lamaran));
        } catch (\Exception $e) {
            report($e);
        }

        return back()->with('success', 'Lamaran ' . $lamaran->nama . ' ditolak. Email pemberitahuan sudah dikirim.');
    }

    // ===== AKUN ADMIN PENDING =====
    public function adminIndex()
    {
        $akunPending = User::where('role', 0)
            ->where('status', 0)
            ->latest()->get();

        return view('backend.v_approval.admin', compact('akunPending'));
    }

    public function adminApprove($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 1]);

        return back()->with('success', 'Akun admin ' . $user->nama . ' disetujui dan sudah bisa login.');
    }

    public function adminReject($id)
    {
        $user = User::findOrFail($id);
        $nama = $user->nama;
        $user->delete(); // belum pernah login, aman dihapus

        return back()->with('success', 'Pendaftaran akun admin ' . $nama . ' ditolak dan dihapus.');
    }
}