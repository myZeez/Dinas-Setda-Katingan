<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContohDokumen extends Model
{
    use HasFactory;

    protected $table = 'contoh_dokumen';

    protected $fillable = [
        'nama',
        'keterangan',
        'file_path',
        'file_name',
        'file_size',
        'jenis_layanan_id',
        'urutan',
        'jumlah_dilihat',
        'jumlah_diunduh',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Jenis Layanan
     */
    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }

    /**
     * Scope untuk dokumen aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Format ukuran file
     */
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    /**
     * Increment view count
     */
    public function incrementView()
    {
        $this->increment('jumlah_dilihat');
    }

    /**
     * Increment download count
     */
    public function incrementDownload()
    {
        $this->increment('jumlah_diunduh');
    }
}
