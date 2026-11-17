<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Menampilkan halaman form pendaftaran
    public function formRegister()
    {
        return view('frontend.v_login.register', [
            'judul' => 'Daftar Akun Baru',
        ]);
    }

    // Memproses penyimpanan akun baru ke database
    public function simpanRegister(Request $request)
    {
        $validatedData = $request->validate([
            'nama'     => 'required|max:255',
            'email'    => 'required|max:255|email|unique:user,email',
            'hp'       => 'required|min:10|max:13',
            'password' => 'required|min:4|confirmed', // Harus sama dengan password_confirmation
        ], [
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email ini sudah terdaftar, gunakan email lain.',
            'hp.required'       => 'Nomor handphone wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal bertaraf 4 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        // Cek kombinasi password (Huruf besar, kecil, angka, simbol) sesuai standar tim kamu
        $password = $request->input('password');
        $pattern  = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';

        if (!preg_match($pattern, $password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Password harus terdiri dari kombinasi huruf besar, huruf kecil, angka, dan simbol.',
            ]);
        }

        // Set default value untuk data akun baru
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['role']     = '2'; // Otomatis mendaftar sebagai User biasa
        $validatedData['status']   = 1;      // Langsung aktif atau set 0 jika butuh approval admin
        $validatedData['foto']     = null;   // Foto kosong dulu saat pertama daftar

        // Simpan ke tabel user
        $user = User::create($validatedData);

        // Buat juga row customer terkait, supaya fitur keranjang/order langsung bisa dipakai
        Customer::create([
            'user_id' => $user->id,
            'alamat'  => null,
            'pos'     => null,
        ]);

        return redirect()->route('customer.login')->with('success', 'Pendaftaran akun sukses! Silakan login.');
    }
}