<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('admin')->check()) {
            return redirect()->route('admin.login');
        }

        if (auth('admin')->user()->role !== 'super_admin') {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
