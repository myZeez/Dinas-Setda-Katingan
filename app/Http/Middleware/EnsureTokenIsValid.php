<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures that a valid API token exists in the session
     * for authenticated web users.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via session
        if (!auth('user')->check()) {
            return redirect()->route('user.login');
        }

        // Check if API token exists in session
        if (!session()->has('api_token')) {
            // Token doesn't exist, create one
            $user = auth('user')->user();
            $token = $user->createToken('WebAccessToken')->accessToken;
            session()->put('api_token', $token);
        }

        return $next($request);
    }
}
