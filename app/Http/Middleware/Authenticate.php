<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        if ($request->is('backend/*') || $request->is('backend')) {
            return route('backend.login');
        }

        // Default: semua route selain backend/* dianggap customer
        return route('customer.login');
    }
}