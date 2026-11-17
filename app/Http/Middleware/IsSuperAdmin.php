<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ((string) auth()->user()->role !== '1') {
            abort(403, 'Khusus Super Admin.');
        }

        return $next($request);
    }
}