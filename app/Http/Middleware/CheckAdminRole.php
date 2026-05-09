<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user udah login DAN role-nya adalah admin
        // (Nah, di sini kita bisa manfaatin helper isAdmin() yang tadi kita buat!)
        if (auth()->check() && auth()->user()->role === 'admin') {
    return $next($request); // Lanjut, silakan masuk
    }

        // Kalau bukan admin, tendang ke halaman 403 (Forbidden)
        abort(403, 'Akses ditolak');
    }
}
