<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Buat akun Super Admin pertama.
     * Data diambil dari .env (SUPERADMIN_NAMA, SUPERADMIN_EMAIL, SUPERADMIN_PASSWORD),
     * jadi password tidak pernah tersimpan di kode / GitHub.
     *
     * Aman dijalankan berulang: kalau email sudah ada, akun TIDAK diubah
     * (password yang sudah Anda ganti tidak ikut tertimpa).
     */
    public function run(): void
    {
        $nama     = env('SUPERADMIN_NAMA', 'Super Admin');
        $email    = env('SUPERADMIN_EMAIL');
        $password = env('SUPERADMIN_PASSWORD');

        if (!$email || !$password) {
            $this->command->error('SUPERADMIN_EMAIL dan SUPERADMIN_PASSWORD belum diisi di file .env');
            return;
        }

        if (strlen($password) < 8) {
            $this->command->error('SUPERADMIN_PASSWORD minimal 8 karakter.');
            return;
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'nama'     => $nama,
                'password' => $password, // di-hash otomatis oleh cast 'hashed' di model User
                'role'     => User::ROLE_SUPERADMIN,
                'status'   => 1,
            ]
        );

        if ($user->wasRecentlyCreated) {
            $this->command->info("Super Admin dibuat: {$email}");
        } else {
            $this->command->warn("Akun dengan email {$email} sudah ada, tidak diubah.");
        }
    }
}