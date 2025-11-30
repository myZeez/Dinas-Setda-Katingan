<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'singkatan',
        'level',
        'parent_id',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Parent unit
    public function parent()
    {
        return $this->belongsTo(UnitKerja::class, 'parent_id');
    }

    // Child units
    public function children()
    {
        return $this->hasMany(UnitKerja::class, 'parent_id')->orderBy('urutan');
    }

    // Pegawai in this unit
    public function pegawais()
    {
        return $this->hasMany(Pegawai::class)->orderBy('is_pimpinan', 'desc')->orderBy('urutan');
    }

    // Get kepala unit
    public function kepala()
    {
        return $this->hasOne(Pegawai::class)->where('is_pimpinan', true);
    }

    // Scope active
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope by level
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Get level label
    public function getLevelLabelAttribute()
    {
        $labels = [
            'sekda' => 'Sekretaris Daerah',
            'asisten' => 'Asisten',
            'bagian' => 'Bagian',
            'subbagian' => 'Sub Bagian',
        ];
        return $labels[$this->level] ?? $this->level;
    }
}
