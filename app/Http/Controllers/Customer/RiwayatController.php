<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('details')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.riwayat.index', compact('pesanan'));
    }
}