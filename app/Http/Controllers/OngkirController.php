<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OngkirController extends Controller
{
    // Ambil semua provinsi (pakai API gratis emsifa, tidak butuh credit)
    public function getProvinces()
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Gagal mengambil data provinsi: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Ambil kabupaten/kota berdasarkan provinsi (pakai API gratis emsifa)
    public function getCities(Request $request)
    {
        $provinceId = $request->input('province_id');

        try {
            $response = Http::withOptions(['verify' => false])
                ->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$provinceId}.json");

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Gagal mengambil data kota: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Hitung ongkos kirim — SIMULASI/DUMMY (tanpa API eksternal, tidak butuh credit)
    public function getCost(Request $request)
    {
        $weight  = (int) $request->input('weight', 1000); // dalam gram
        $courier = strtolower($request->input('courier', 'jne'));

        // Konversi berat ke kelipatan kg (dibulatkan ke atas), minimal 1 kg
        $beratKg = max(1, ceil($weight / 1000));

        // Tarif dasar per kg (dummy, angka realistis kasar per kurir)
        $tarifDasar = [
            'jne'      => ['ongkir_per_kg' => 9000,  'layanan' => [
                ['nama' => 'REG',  'multiplier' => 1,   'estimasi' => '2-3 hari'],
                ['nama' => 'YES',  'multiplier' => 1.6, 'estimasi' => '1-2 hari'],
                ['nama' => 'OKE',  'multiplier' => 0.8, 'estimasi' => '3-5 hari'],
            ]],
            'tiki'     => ['ongkir_per_kg' => 8500,  'layanan' => [
                ['nama' => 'REG',  'multiplier' => 1,   'estimasi' => '2-4 hari'],
                ['nama' => 'ONS',  'multiplier' => 1.8, 'estimasi' => '1 hari'],
            ]],
            'pos'      => ['ongkir_per_kg' => 7500,  'layanan' => [
                ['nama' => 'Paket Kilat Khusus', 'multiplier' => 1,   'estimasi' => '3-6 hari'],
                ['nama' => 'Express Next Day',   'multiplier' => 2,   'estimasi' => '1 hari'],
            ]],
            'jnt'      => ['ongkir_per_kg' => 8000,  'layanan' => [
                ['nama' => 'EZ',   'multiplier' => 1,   'estimasi' => '2-4 hari'],
            ]],
            'sicepat'  => ['ongkir_per_kg' => 8200,  'layanan' => [
                ['nama' => 'REG',  'multiplier' => 1,   'estimasi' => '2-3 hari'],
                ['nama' => 'BEST', 'multiplier' => 1.5, 'estimasi' => '1 hari'],
            ]],
        ];

        if (!isset($tarifDasar[$courier])) {
            return response()->json([
                'status'  => 400,
                'message' => 'Kurir tidak dikenali.',
            ]);
        }

        $data = $tarifDasar[$courier];
        $costs = [];

        foreach ($data['layanan'] as $layanan) {
            $harga = round($beratKg * $data['ongkir_per_kg'] * $layanan['multiplier'], -2); // dibulatkan ke ratusan

            $costs[] = [
                'service'   => $layanan['nama'],
                'price'     => (int) $harga,
                'estimated' => $layanan['estimasi'],
            ];
        }

        // Struktur respons dibuat mirip format lama (Binderbyte-style) supaya
        // JS di ongkir.blade.php / pengiriman.blade.php tidak perlu diubah lagi.
        return response()->json([
            'status' => 200,
            'data'   => [
                'costs' => $costs,
            ],
        ]);
    }
}