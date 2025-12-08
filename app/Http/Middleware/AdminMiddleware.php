<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check if user is admin or super_admin
        if (!in_array(Auth::guard('admin')->user()->role, ['admin', 'super_admin'])) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Anda bukan administrator.');
        }

        return $next($request);
    }
}
