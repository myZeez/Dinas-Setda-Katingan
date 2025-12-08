<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display admin users
     */
    public function adminIndex(Request $request)
    {
        $query = User::whereIn('role', ['super_admin', 'admin'])->orderBy('created_at', 'desc');

        // Filter by role
        if ($request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10);

        $stats = [
            'total' => User::whereIn('role', ['super_admin', 'admin'])->count(),
            'super_admin' => User::where('role', 'super_admin')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'active' => User::whereIn('role', ['super_admin', 'admin'])->where('is_active', true)->count(),
        ];

        return view('admin.users.admin-index', compact('users', 'stats'));
    }

    /**
     * Display user masyarakat
     */
    public function userIndex(Request $request)
    {
        $query = User::where('role', 'user')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%')
                  ->orWhere('instansi', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10);

        $stats = [
            'total' => User::where('role', 'user')->count(),
            'active' => User::where('role', 'user')->where('is_active', true)->count(),
            'inactive' => User::where('role', 'user')->where('is_active', false)->count(),
            'today' => User::where('role', 'user')->whereDate('created_at', today())->count(),
        ];

        return view('admin.users.user-index', compact('users', 'stats'));
    }

    /**
     * Show create admin form
     */
    public function createAdmin()
    {
        return view('admin.users.admin-create');
    }

    /**
     * Store new admin
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        // Handle foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = 'admin_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['foto'] = $file->storeAs('admin-photos', $fileName, 'public');
        }

        $user = User::create($validated);

        ActivityLog::log('create', 'user', "Menambahkan admin baru: {$user->name}");

        return redirect()->route('admin.users.admin')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Show edit admin form
     */
    public function editAdmin($id)
    {
        $user = User::whereIn('role', ['super_admin', 'admin'])->findOrFail($id);
        return view('admin.users.admin-edit', compact('user'));
    }

    /**
     * Update admin
     */
    public function updateAdmin(Request $request, $id)
    {
        $user = User::whereIn('role', ['super_admin', 'admin'])->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Handle password
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle foto
        if ($request->hasFile('foto')) {
            // Delete old foto
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $file = $request->file('foto');
            $fileName = 'admin_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['foto'] = $file->storeAs('admin-photos', $fileName, 'public');
        }

        $oldData = $user->toArray();
        $user->update($validated);

        ActivityLog::log('update', 'user', "Mengubah data admin: {$user->name}", $oldData, $user->toArray());

        return redirect()->route('admin.users.admin')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Delete admin
     */
    public function destroyAdmin($id)
    {
        $user = User::whereIn('role', ['super_admin', 'admin'])->findOrFail($id);

        // Prevent deleting self
        if ($user->id === auth('admin')->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Prevent deleting last super admin
        if ($user->role === 'super_admin') {
            $superAdminCount = User::where('role', 'super_admin')->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat menghapus super admin terakhir.');
            }
        }

        // Delete foto
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        ActivityLog::log('delete', 'user', "Menghapus admin: {$user->name}");

        $user->delete();

        return redirect()->route('admin.users.admin')
            ->with('success', 'Admin berhasil dihapus.');
    }

    /**
     * Show user detail
     */
    public function showUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        return view('admin.users.user-show', compact('user'));
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Prevent toggling self
        if ($user->id === auth('admin')->id()) {
            return back()->with('error', 'Tidak dapat mengubah status akun sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::log('update', 'user', "Status user {$user->name} {$status}");

        return back()->with('success', "Status user berhasil {$status}.");
    }

    /**
     * Delete user masyarakat
     */
    public function destroyUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        ActivityLog::log('delete', 'user', "Menghapus user: {$user->name}");

        $user->delete();

        return redirect()->route('admin.users.user')
            ->with('success', 'User berhasil dihapus.');
    }
}
