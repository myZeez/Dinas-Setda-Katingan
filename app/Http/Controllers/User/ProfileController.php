<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show profile
     */
    public function index()
    {
        $user = Auth::guard('user')->user();
        return view('user.profile.index', compact('user'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = Auth::guard('user')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'no_whatsapp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'instansi' => 'required|string|max:255',
            'biro_bagian' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user = Auth::guard('user')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
