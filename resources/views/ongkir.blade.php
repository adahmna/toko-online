<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OngkirController extends Controller
{
    // Ambil semua provinsi (pakai API gratis emsifa, tidak butuh credit)
    public function getProvinces()
    {
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');

        return response()->json($response->json());
    }

    // Ambil kabupaten/kota berdasarkan provinsi (pakai API gratis emsifa)
    public function getCities(Request $request)
    {
        $provinceId = $request->input('province_id');

        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json");

        return response()->json($response->json());
    }

    // Hitung ongkos kirim (tetap pakai Binderbyte, butuh credit/top-up)
    public function getCost(Request $request)
    {
        $origin      = $request->input('origin');
        $destination = $request->input('destination');
        $weight      = $request->input('weight');
        $courier     = $request->input('courier');

        $response = Http::get('https://api.binderbyte.com/v1/cost', [
            'api_key'     => env('BINDERBYTE_API_KEY'),
            'courier'     => $courier,
            'origin'      => $origin,
            'destination' => $destination,
            'weight'      => $weight,
        ]);

        return response()->json($response->json());
    }
}
