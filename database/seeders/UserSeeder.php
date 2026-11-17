<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        User::create([
            'nama'     => 'Ade Ahmad Maulana',
            'email'    => 'adety408@admin.com',
            'role'     => '1',
            'status'   => 1,
            'hp'       => '081299597075',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'nama'     => 'Muhammad Al Haidar',
            'email'    => 'haidar1@admin.com',
            'role'     => '0',
            'status'   => 1,
            'hp'       => '085720296284',
            'password' => Hash::make('password123'),
        ]);
    }
}