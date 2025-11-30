<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InformasiPublik extends Model
{
    use HasFactory;

    protected $table = 'informasi_publiks';

    protected $fillable = [
        'judul',
        'nomor',
        'tanggal',
        'keterangan',
        'file_dokumen',
        'file_lampiran',
        'lampiran_label',
        'kategori_informasi_id',
        'jenis_dokumen_id',
        'status',
        'views',
        'downloads',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_active' => 'boolean',
    ];

    // Kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriInformasi::class, 'kategori_informasi_id');
    }

    // Jenis Dokumen
    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_dokumen_id');
    }

    // User/Author
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope active
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get file URL
    public function getFileDokumenUrlAttribute()
    {
        return $this->file_dokumen ? Storage::url($this->file_dokumen) : null;
    }

    public function getFileLampiranUrlAttribute()
    {
        return $this->file_lampiran ? Storage::url($this->file_lampiran) : null;
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'berlaku' => 'Berlaku',
            'tidak_berlaku' => 'Tidak Berlaku',
            'terealisasi' => 'Terealisasi',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    // Get status color
    public function getStatusColorAttribute()
    {
        $colors = [
            'berlaku' => 'success',
            'tidak_berlaku' => 'secondary',
            'terealisasi' => 'info',
        ];
        return $colors[$this->status] ?? 'secondary';
    }

    // Format tanggal
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d-m-Y') : '-';
    }

    // Increment view
    public function incrementView()
    {
        $this->increment('views');
    }

    // Increment download
    public function incrementDownload()
    {
        $this->increment('downloads');
    }
}
