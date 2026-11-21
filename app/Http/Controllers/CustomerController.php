<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class CustomerController extends Controller
{
    private const FOTO_DIR = 'storage/img-customer/';

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback dari Google
    public function callback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();

            $registeredUser = User::where('email', $socialUser->email)->first();

            if (!$registeredUser) {
                $user = User::create([
                    'nama'     => $socialUser->name,
                    'email'    => $socialUser->email,
                    'role'     => User::ROLE_CUSTOMER,
                    'status'   => 1,
                    'password' => Hash::make('default_password'),
                ]);

                Customer::create([
                    'user_id'      => $user->id,
                    'google_id'    => $socialUser->id,
                    'google_token' => $socialUser->token,
                ]);

                Auth::login($user);
            } else {
                Auth::login($registeredUser);
            }

            return redirect()->intended('beranda');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    public function index()
    {
        return view('backend.v_customer.index', [
            'judul' => 'Customer',
            'sub'   => 'Halaman Customer',
            'index' => Customer::orderBy('id', 'desc')->get(),
        ]);
    }

    public function akun($id)
    {
        $loggedInId = Auth::id();

        // Hanya boleh mengakses akun sendiri
        if ($id != $loggedInId) {
            return redirect()->route('customer.akun', ['id' => $loggedInId])
                ->with('msgError', 'Anda tidak berhak mengakses akun ini.');
        }

        return view('v_customer.edit', [
            'judul'    => 'Customer',
            'subJudul' => 'Akun Customer',
            'edit'     => $this->findOrCreateCustomer($id),
        ]);
    }

    public function updateAkun(Request $request, $id)
    {
        $customer = $this->findOrCreateCustomer($id);
        $user     = $customer->user;

        $rules = [
            'nama' => 'required|max:255',
            'hp'   => 'required|min:10|max:13',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];

        $messages = [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max'   => 'Ukuran file gambar Maksimal adalah 1024 KB.',
        ];

        if ($request->email != $user->email) {
            $rules['email'] = 'required|max:255|email|unique:user,email,' . $user->id;
        }

        if ($request->alamat != $customer->alamat) {
            $rules['alamat'] = 'required';
        }

        if ($request->pos != $customer->pos) {
            $rules['pos'] = 'required';
        }

        $validatedData = $request->validate($rules, $messages);

        // Data untuk tabel user (tanpa alamat & pos)
        $userData = collect($validatedData)->only(['nama', 'email', 'hp'])->all();

        if ($request->file('foto')) {
            $this->hapusFoto($user->foto);

            $file     = $request->file('foto');
            $fileName = date('YmdHis') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            ImageHelper::uploadAndResize($file, self::FOTO_DIR, $fileName, 385, 400);

            $userData['foto'] = $fileName;
        } elseif ($request->boolean('hapus_foto')) {
            $this->hapusFoto($user->foto);
            $userData['foto'] = null;
        }

        $user->update($userData);

        $customer->update([
            'alamat' => $request->input('alamat'),
            'pos'    => $request->input('pos'),
        ]);

        return redirect()->route('customer.akun', $id)->with('success', 'Data berhasil diperbarui');
    }

    private function findOrCreateCustomer($userId): Customer
    {
        return Customer::firstOrCreate(
            ['user_id' => $userId],
            ['alamat' => '-', 'pos' => '-']
        );
    }

    private function hapusFoto(?string $foto): void
    {
        if (!$foto) {
            return;
        }

        $path = public_path(self::FOTO_DIR) . $foto;

        if (file_exists($path)) {
            unlink($path);
        }
    }
}