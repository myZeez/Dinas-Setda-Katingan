<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::guard('user')->check() && Auth::guard('user')->user()->isUser()) {
            return redirect()->route('user.dashboard');
        }
        return view('user.auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('user')->attempt($credentials, $request->remember)) {
            $user = Auth::guard('user')->user();

            // Check if user role
            if ($user->role !== 'user') {
                Auth::guard('user')->logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun pengguna layanan.',
                ])->onlyInput('email');
            }

            if (!$user->is_active) {
                Auth::guard('user')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ])->onlyInput('email');
            }

            // Create Passport token
            $token = $user->createToken('UserAccessToken')->accessToken;

            // Store token in session for web usage
            $request->session()->put('api_token', $token);
            $request->session()->regenerate();

            // Log activity
            ActivityLog::log(
                'login',
                'auth',
                "User {$user->name} berhasil login ke sistem",
                null,
                null,
                'user'
            );

            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * API Login - Returns token for API access
     */
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::guard('user')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $user = Auth::guard('user')->user();

        if ($user->role !== 'user') {
            Auth::guard('user')->logout();
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun pengguna layanan.',
            ], 403);
        }

        if (!$user->is_active) {
            Auth::guard('user')->logout();
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ], 403);
        }

        // Create Passport token
        $token = $user->createToken('UserAccessToken')->accessToken;

        // Log activity
        ActivityLog::log(
            'login',
            'auth',
            "User {$user->name} berhasil login melalui API",
            null,
            null,
            'user'
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

    /**
     * Show registration form
     */
    public function showRegister()
    {
        if (Auth::guard('user')->check() && Auth::guard('user')->user()->isUser()) {
            return redirect()->route('user.dashboard');
        }
        return view('user.auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'nik' => 'required|string|max:20|unique:users,nik',
            'email' => 'required|email|unique:users,email',
            'no_whatsapp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'instansi' => 'required|string|max:255',
            'biro_bagian' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'nik.required' => 'NIK wajib diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'no_whatsapp.required' => 'No. WhatsApp wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'instansi.required' => 'Instansi wajib diisi',
            'biro_bagian.required' => 'Biro/Bagian/Bidang/Seksi wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'jabatan' => $validated['jabatan'],
            'nip' => $validated['nip'],
            'nik' => $validated['nik'],
            'email' => $validated['email'],
            'no_whatsapp' => $validated['no_whatsapp'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'instansi' => $validated['instansi'],
            'biro_bagian' => $validated['biro_bagian'],
            'role' => 'user',
            'is_active' => true,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('user')->login($user);

        // Create Passport token for new user
        $token = $user->createToken('UserAccessToken')->accessToken;
        $request->session()->put('api_token', $token);

        // Log activity
        ActivityLog::log(
            'create',
            'auth',
            "User baru {$user->name} berhasil mendaftar",
            null,
            null,
            'user'
        );

        return redirect()->route('user.dashboard')->with('success', 'Selamat! Akun Anda berhasil dibuat.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $user = Auth::guard('user')->user();

        // Log activity before logout
        if ($user) {
            ActivityLog::log(
                'logout',
                'auth',
                "User {$user->name} logout dari sistem",
                null,
                null,
                'user'
            );
        }

        // Revoke all tokens
        if ($user) {
            $user->tokens()->delete();
        }

        Auth::guard('user')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }

    /**
     * API Logout - Revoke token
     */
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

    /**
     * Get current authenticated user profile (API)
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }
}
