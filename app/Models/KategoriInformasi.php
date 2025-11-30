<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriInformasi extends Model
{
    use HasFactory;

    protected $table = 'kategori_informasis';

    protected $fillable = [
        'nama',
        'slug',
        'icon',
        'deskripsi',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Generate slug
    public static function generateSlug($nama)
    {
        return Str::slug($nama);
    }

    // Jenis dokumen
    public function jenisDokumens()
    {
        return $this->hasMany(JenisDokumen::class, 'kategori_informasi_id')->orderBy('urutan');
    }

    // Informasi publik
    public function informasiPubliks()
    {
        return $this->hasMany(InformasiPublik::class, 'kategori_informasi_id');
    }

    // Scope active
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
