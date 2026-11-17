<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanduanPengirimanController extends Controller
{
    public function index()
    {
        return view('panduan-pengiriman');
    }
}
