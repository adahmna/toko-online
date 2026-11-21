<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'password' => [
                'required',
                'min:4',
                'confirmed',
                // Harus ada huruf besar, huruf kecil, angka, dan simbol
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ], [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email ini sudah terdaftar, gunakan email lain.',
            'hp.required'        => 'Nomor handphone wajib diisi.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 4 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex'     => 'Password harus terdiri dari kombinasi huruf besar, huruf kecil, angka, dan simbol.',
        ]);

        // User dan customer dibuat satu paket: kalau salah satu gagal, keduanya dibatalkan
        DB::transaction(function () use ($validatedData) {
            $user = User::create([
                'nama'     => $validatedData['nama'],
                'email'    => $validatedData['email'],
                'hp'       => $validatedData['hp'],
                'password' => Hash::make($validatedData['password']),
                'role'     => User::ROLE_CUSTOMER,
                'status'   => 1,
                'foto'     => null,
            ]);

            Customer::create([
                'user_id' => $user->id,
                'alamat'  => '-',
                'pos'     => '-',
            ]);
        });

        return redirect()->route('customer.login')
            ->with('success', 'Pendaftaran akun sukses! Silakan login.');
    }
}