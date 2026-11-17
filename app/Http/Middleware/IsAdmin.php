<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login dan merupakan Super Admin atau Admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Jika pembeli (customer) nyasar ke area backend, tendang ke halaman beranda depan
        return redirect('/')->with('error', 'Anda tidak memiliki hak akses manajemen toko.');
    }
}