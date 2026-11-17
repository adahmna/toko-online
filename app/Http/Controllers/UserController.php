<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Mapping kode role -> label, dipakai supaya konsisten di seluruh controller.
     * Sesuaikan urutan/label ini dengan definisi sebenarnya di project kamu.
     */
    private const ROLE_LABELS = [
        '0' => 'Admin',
        '1' => 'Super Admin',
        '2' => 'Customer',
    ];

    /**
     * Semua akun selalu Aktif. Opsi NonAktif sudah dihapus;
     * untuk mematikan akun gunakan tombol Hapus.
     */
    private const STATUS_AKTIF = 1;

    /**
     * Hak akses: hanya Super Admin yang boleh tambah, ubah, dan hapus user.
     * Admin biasa hanya bisa melihat daftar user (index), laporan, dan profil sendiri.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = $request->user();

            if (!$user || (string) $user->role !== User::ROLE_SUPERADMIN) {
                abort(403, 'Hanya Super Admin yang dapat mengelola data user.');
            }

            return $next($request);
        })->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy('updated_at', 'desc')->get();
        return view('backend.v_user.index', [
            'judul' => 'Data User',
            'index' => $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.v_user.create', [
            'judul' => 'Tambah User',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'     => 'required|max:255',
            'email'    => 'required|max:255|email|unique:user',
            'role'     => 'required|in:0,1,2',
            'hp'       => 'required|min:10|max:13',
            'password' => 'required|min:4|confirmed',
            'foto'     => 'nullable|image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ], [
            'role.in'    => 'Role yang dipilih tidak valid.',
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max'   => 'Ukuran file gambar Maksimal adalah 1024 KB.',
        ]);

        // akun baru langsung Aktif
        $validatedData['status'] = self::STATUS_AKTIF;

        if ($request->file('foto')) {
            $validatedData['foto'] = $this->simpanFoto($request->file('foto'));
        }

        $password = $request->input('password');
        $pattern  = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';

        if (!preg_match($pattern, $password)) {
            return redirect()->back()->withErrors([
                'password' => 'Password harus terdiri dari kombinasi huruf besar, huruf kecil, angka, dan simbol karakter.',
            ]);
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
        User::create($validatedData);

        return redirect()->route('backend.user.index')->with('success', 'Data berhasil tersimpan');
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
    public function edit($id)
    {
        $edit  = User::findOrFail($id);
        $judul = 'Edit User';

        return view('backend.v_user.edit', compact('edit', 'judul'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'nama' => 'required|max:255',
            'role' => 'required|in:0,1,2',
            'hp'   => 'required|min:10|max:13',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];

        $messages = [
            'role.in'    => 'Role yang dipilih tidak valid.',
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max'   => 'Ukuran file gambar Maksimal adalah 1024 KB.',
        ];

        if ($request->email != $user->email) {
            $rules['email'] = 'required|max:255|email|unique:user';
        }

        $validatedData = $request->validate($rules, $messages);

        // status tidak lagi dipilih lewat form: selalu Aktif
        $validatedData['status'] = self::STATUS_AKTIF;

        if ($request->file('foto')) {
            $this->hapusFotoLama($user->foto);
            $validatedData['foto'] = $this->simpanFoto($request->file('foto'));
        }

        $user->update($validatedData);

        return redirect()->route('backend.user.index')->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $this->hapusFotoLama($user->foto);
        $user->delete();

        return redirect()->route('backend.user.index')->with('success', 'Data berhasil dihapus');
    }

    /**
     * Menampilkan form laporan user.
     */
    public function formUser()
    {
        return view('backend.v_user.form', [
            'judul' => 'Laporan Data User',
        ]);
    }

    /**
     * Mencetak dokumen laporan user berdasarkan rentang tanggal.
     */
    public function cetakUser(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ], [
            'tanggal_awal.required'        => 'Tanggal Awal harus diisi.',
            'tanggal_akhir.required'       => 'Tanggal Akhir harus diisi.',
            'tanggal_akhir.after_or_equal' => 'Tanggal Akhir harus lebih besar atau samadengan Tanggal Awal.',
        ]);

        $tanggalAwal  = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $user = User::whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.v_user.cetak', [
            'judul'        => 'Laporan User',
            'tanggalAwal'  => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'cetak'        => $user,
        ]);
    }

    /**
     * Menampilkan form edit profil user yang sedang login.
     */
    public function editProfil()
    {
        $user = auth()->user();

        return view('backend.v_user.profil', [
            'judul' => 'Edit Profil Saya',
            'edit'  => $user,
        ]);
    }

    /**
     * Menyimpan perubahan data profil ke database beserta Foto & TTL.
     */
    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'nama'          => 'required|max:255',
            'email'         => 'required|email|max:255|unique:user,email,' . $user->id,
            'hp'            => 'required|max:15',
            'tempat_lahir'  => 'required|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required|max:500',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|min:4';
        }

        $validatedData = $request->validate($rules, [
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'hp.required'            => 'Nomor handphone wajib diisi.',
            'tempat_lahir.required'  => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'foto.image'             => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max'               => 'Ukuran file gambar Maksimal adalah 1024 KB.',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password);
        } else {
            unset($validatedData['password']);
        }

        if ($request->file('foto')) {
            $this->hapusFotoLama($user->foto);
            $validatedData['foto'] = $this->simpanFoto($request->file('foto'));
        }

        User::where('id', $user->id)->update($validatedData);

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Helper: simpan & resize foto upload, kembalikan nama file barunya.
     * Dipakai oleh store(), update(), dan updateProfil() supaya tidak duplikat kode.
     */
    private function simpanFoto($file): string
    {
        $extension        = $file->getClientOriginalExtension();
        $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
        $directory         = 'storage/img-user/';

        ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);

        return $originalFileName;
    }

    /**
     * Helper: hapus foto lama dari storage jika ada.
     */
    private function hapusFotoLama(?string $namaFoto): void
    {
        if (!$namaFoto) {
            return;
        }

        $oldImagePath = public_path('storage/img-user/') . $namaFoto;
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }
}