<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Produk;
use App\Models\Kategori;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
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

        // Kategori
        Kategori::truncate();

        Kategori::create(['nama_kategori' => 'Makanan Ringan']);
        Kategori::create(['nama_kategori' => 'Makanan Berat']);

        // Produk
        Produk::truncate();

        Produk::create([
            'kategori_id' => 1,
            'user_id'     => 1,
            'status'      => 1,
            'nama_produk' => 'Brownies Coklat',
            'detail'      => 'Brownies coklat lezat dan nikmat',
            'harga'       => 50000,
            'stok'        => 100,
            'berat'       => 0.5,
            'foto'        => 'default.jpg',
        ]);

        Produk::create([
            'kategori_id' => 2,
            'user_id'     => 1,
            'status'      => 1,
            'nama_produk' => 'Combro Pedas',
            'detail'      => 'Combro pedas khas Jawa Barat',
            'harga'       => 20000,
            'stok'        => 50,
            'berat'       => 0.3,
            'foto'        => 'default.jpg',
        ]);
    }
}