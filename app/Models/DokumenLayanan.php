<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenLayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_layanan_id',
        'jenis_dokumen',
        'nama_file',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'catatan',
        'versi',
    ];

    // Jenis Dokumen Labels
    const JENIS_DOKUMEN_LABELS = [
        'surat_penawaran' => 'Surat Penawaran',
        'kerangka_acuan_kerja' => 'Kerangka Acuan Kerja (KAK)',
        'draft_naskah' => 'Draft Naskah PKS/Nota Kesepakatan',
    ];

    // Status Labels
    const STATUS_LABELS = [
        'diterima' => 'Diterima',
        'diproses' => 'Diproses',
        'koreksi' => 'Mohon Diperbaiki/Koreksi',
        'ditolak' => 'Ditolak',
    ];

    // Status Colors
    const STATUS_COLORS = [
        'diterima' => 'success',
        'diproses' => 'primary',
        'koreksi' => 'warning',
        'ditolak' => 'danger',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanLayanan::class, 'pengajuan_layanan_id');
    }

    public function getJenisDokumenLabelAttribute()
    {
        return self::JENIS_DOKUMEN_LABELS[$this->jenis_dokumen] ?? $this->jenis_dokumen;
    }

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

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
}
