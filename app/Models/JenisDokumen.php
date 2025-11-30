<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JenisDokumen extends Model
{
    use HasFactory;

    protected $table = 'jenis_dokumens';

    protected $fillable = [
        'nama',
        'slug',
        'kategori_informasi_id',
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

    // Kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriInformasi::class, 'kategori_informasi_id');
    }

    // Informasi publik
    public function informasiPubliks()
    {
        return $this->hasMany(InformasiPublik::class, 'jenis_dokumen_id');
    }

    // Scope active
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
