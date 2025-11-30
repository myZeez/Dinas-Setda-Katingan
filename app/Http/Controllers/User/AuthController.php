<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        if (Auth::check() && Auth::user()->isUser()) {
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

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            // Check if user role
            if ($user->role !== 'user') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun pengguna layanan.',
                ])->onlyInput('email');
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        if (Auth::check() && Auth::user()->isUser()) {
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

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Selamat! Akun Anda berhasil dibuat.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
}
