<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LamaranKerja;

class LoginController extends Controller
{
    // Tampilkan halaman login ADMIN
    public function loginBackend()
    {
        return view('backend.v_login.login');
    }

    // Tampilkan halaman login CUSTOMER (form + Google, tema toko)
    public function showCustomerLogin()
    {
        return view('frontend.v_login.login');
    }

    // Proses login (dipakai bareng oleh form admin & form customer)
    public function authenticateBackend(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $namaRoute = $request->route()->getName();
            $user      = auth()->user();
            $role      = (string) $user->role;

            // Login lewat form ADMIN tapi akunnya customer -> tolak
            if ($namaRoute === 'backend.login.post' && $role === '2') {
                $this->batalkanLogin($request);
                return back()->withErrors([
                    'email' => 'Anda bukan admin.',
                ])->onlyInput('email');
            }

            // Login lewat form CUSTOMER tapi akunnya admin -> tolak
            if ($namaRoute === 'customer.login.post' && $role !== '2') {
                $this->batalkanLogin($request);
                return back()->withErrors([
                    'email' => 'Anda bukan customer.',
                ])->onlyInput('email');
            }

            // Akun admin (role 0/1) tapi status masih pending -> tolak
            if ($role !== '2' && (int) $user->status !== 1) {
                $this->batalkanLogin($request);
                return back()->withErrors([
                    'email' => 'Akun kamu masih menunggu persetujuan Super Admin.',
                ])->onlyInput('email');
            }

            if ($role === '2') {
                return redirect()->intended(route('beranda'));
            }

            return redirect()->route('backend.beranda');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    private function batalkanLogin(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    // Proses logout
    public function logoutBackend(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('backend.login');
    }

    // Tampilkan halaman register ADMIN
    public function showRegister()
    {
        $judul = 'Register';
        return view('backend.v_login.register', compact('judul'));
    }

    // Proses register (khusus ADMIN, lewat /backend/register)
    public function prosesRegister(Request $request)
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:user,email'],
            'hp'       => ['required', 'numeric'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        // Cek status lamaran kerja untuk email ini
        $lamaran = LamaranKerja::where('email', $request->email)
            ->latest()
            ->first();

        if (!$lamaran) {
            return redirect()->route('karir.lamar')
                ->with('info', 'Kamu belum pernah melamar kerja. Silakan lamar dulu sebelum mendaftar sebagai admin.')
                ->withInput();
        }

        if ($lamaran->status === LamaranKerja::STATUS_PENDING) {
            return redirect()->route('karir.index')
                ->with('info', 'Lamaran kamu masih menunggu review dari Super Admin. Silakan tunggu sampai disetujui.');
        }

        if ($lamaran->status === LamaranKerja::STATUS_DITOLAK) {
            return redirect()->route('karir.lamar')
                ->with('info', 'Lamaran kamu sebelumnya ditolak. Silakan lamar ulang dengan data yang lebih lengkap.')
                ->withInput();
        }

        // Pastikan lamaran yang diterima ini belum pernah dipakai daftar akun lain
        $sudahDipakai = User::where('lamaran_id', $lamaran->id)->exists();
        if ($sudahDipakai) {
            return back()->withErrors([
                'email' => 'Email ini sudah pernah dipakai untuk mendaftar akun admin.',
            ])->onlyInput('email', 'nama', 'hp');
        }

        // Lamaran diterima & belum dipakai -> buat akun, status pending approval admin
        $user = User::create([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'hp'         => $request->hp,
            'password'   => Hash::make($request->password),
            'role'       => 0,     // Admin biasa
            'status'     => 0,     // Pending, menunggu approval Super Admin
            'lamaran_id' => $lamaran->id,
        ]);

        return redirect()->route('backend.login')
            ->with('success', 'Pendaftaran berhasil! Akun kamu menunggu persetujuan HRD/Super Admin sebelum bisa login.');
    }
}