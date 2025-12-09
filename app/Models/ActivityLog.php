<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action label
     */
    public function getActionLabelAttribute()
    {
        $labels = [
            'login' => 'Login',
            'logout' => 'Logout',
            'create' => 'Tambah Data',
            'update' => 'Edit Data',
            'delete' => 'Hapus Data',
            'view' => 'Lihat Data',
            'download' => 'Download',
            'upload' => 'Upload',
            'approve' => 'Setujui',
            'reject' => 'Tolak',
        ];

        return $labels[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Get action color
     */
    public function getActionColorAttribute()
    {
        $colors = [
            'login' => 'success',
            'logout' => 'secondary',
            'create' => 'primary',
            'update' => 'warning',
            'delete' => 'danger',
            'view' => 'info',
            'download' => 'info',
            'upload' => 'primary',
            'approve' => 'success',
            'reject' => 'danger',
        ];

        return $colors[$this->action] ?? 'secondary';
    }

    /**
     * Get module label
     */
    public function getModuleLabelAttribute()
    {
        $labels = [
            'auth' => 'Autentikasi',
            'berita' => 'Berita',
            'layanan' => 'Layanan',
            'informasi_publik' => 'Informasi Publik',
            'struktur' => 'Struktur Organisasi',
            'user' => 'Pengguna',
            'settings' => 'Pengaturan',
        ];

        return $labels[$this->module] ?? ucfirst(str_replace('_', ' ', $this->module));
    }

    /**
     * Static method to log activity
     */
    public static function log($action, $module, $description, $oldData = null, $newData = null, $guard = null)
    {
        // Use specified guard or try to detect
        if ($guard) {
            $user = auth($guard)->user();
        } else {
            // Try admin guard first, then user guard
            $user = auth('admin')->user() ?? auth('user')->user();
        }

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'Guest',
            'user_role' => $user?->role ?? 'guest',
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
