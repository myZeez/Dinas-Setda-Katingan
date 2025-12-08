<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('user')->check()) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (Auth::guard('user')->user()->role !== 'user') {
            Auth::guard('user')->logout();
            return redirect()->route('user.login')->with('error', 'Akses ditolak.');
        }

        if (!Auth::guard('user')->user()->is_active) {
            Auth::guard('user')->logout();
            return redirect()->route('user.login')->with('error', 'Akun Anda tidak aktif.');
        }

        return $next($request);
    }
}
