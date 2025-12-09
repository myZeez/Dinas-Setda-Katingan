<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            $user = Auth::guard('admin')->user();

            // Create Passport token
            $token = $user->createToken('AdminAccessToken')->accessToken;

            // Store token in session for web usage
            $request->session()->put('api_token', $token);
            $request->session()->regenerate();

            // Log activity
            ActivityLog::log(
                'login',
                'auth',
                "Admin {$user->name} berhasil login ke sistem",
                null,
                null,
                'admin'
            );

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::guard('admin')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $user = Auth::guard('admin')->user();

        if (!in_array($user->role, ['admin', 'super_admin'])) {
            Auth::guard('admin')->logout();
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun administrator.',
            ], 403);
        }

        // Create Passport token
        $token = $user->createToken('AdminAccessToken')->accessToken;

        // Log activity
        ActivityLog::log(
            'login',
            'auth',
            "Admin {$user->name} berhasil login melalui API",
            null,
            null,
            'admin'
        );

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();

        // Log activity before logout
        if ($user) {
            ActivityLog::log(
                'logout',
                'auth',
                "Admin {$user->name} logout dari sistem",
                null,
                null,
                'admin'
            );
        }

        // Revoke all tokens
        if ($user) {
            $user->tokens()->delete();
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function apiLogout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // Revoke current token
            $user->token()->revoke();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }
}