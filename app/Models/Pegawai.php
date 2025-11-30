<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'golongan',
        'foto',
        'unit_kerja_id',
        'is_pimpinan',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_pimpinan' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Unit kerja
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    // Scope active
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get foto URL
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return Storage::url($this->foto);
        }
        return asset('images/default-avatar.png');
    }

    // Get formatted NIP
    public function getFormattedNipAttribute()
    {
        if (!$this->nip) return '-';
        // Format: 19850905 200312 2 003
        $nip = preg_replace('/[^0-9]/', '', $this->nip);
        if (strlen($nip) == 18) {
            return substr($nip, 0, 8) . ' ' . substr($nip, 8, 6) . ' ' . substr($nip, 14, 1) . ' ' . substr($nip, 15, 3);
        }
        return $this->nip;
    }
}
