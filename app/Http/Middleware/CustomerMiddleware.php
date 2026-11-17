<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     * Mengizinkan semua role yang sudah login (superadmin, admin, customer).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('backend.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $allowedRoles = [
            \App\Models\User::ROLE_SUPERADMIN,
            \App\Models\User::ROLE_ADMIN,
            \App\Models\User::ROLE_CUSTOMER,
        ];

        if (!in_array(auth()->user()->role, $allowedRoles)) {
            return redirect()->route('backend.login')
                ->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}