<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanLayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_pengajuan',
        'user_id',
        'jenis_layanan_id',
        'nama_pihak',
        'tentang',
        'instansi_terkait',
        'status',
        'catatan_admin',
        'dokumen_hasil',
        'tanggal_pengajuan',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    // Status Labels
    const STATUS_LABELS = [
        'draft' => 'Draft',
        'diajukan' => 'Diajukan',
        'diproses' => 'Sedang Diproses',
        'koreksi' => 'Mohon Diperbaiki/Koreksi',
        'proses_ttd' => 'Proses Penandatanganan',
        'penjadwalan_ttd' => 'Penjadwalan Penandatanganan',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak',
    ];

    // Status Colors for Badge
    const STATUS_COLORS = [
        'draft' => 'secondary',
        'diajukan' => 'info',
        'diproses' => 'primary',
        'koreksi' => 'warning',
        'proses_ttd' => 'info',
        'penjadwalan_ttd' => 'info',
        'selesai' => 'success',
        'ditolak' => 'danger',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class);
    }

    public function dokumens()
    {
        return $this->hasMany(DokumenLayanan::class);
    }

    public function logs()
    {
        return $this->hasMany(LogPengajuan::class)->orderBy('created_at', 'desc');
    }

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    // Generate Nomor Pengajuan
    public static function generateNomorPengajuan($jenisLayananKode)
    {
        $tahun = date('Y');
        $bulan = date('m');
        $prefix = strtoupper($jenisLayananKode);

        $lastPengajuan = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->where('nomor_pengajuan', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPengajuan) {
            $lastNumber = intval(substr($lastPengajuan->nomor_pengajuan, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "{$prefix}/{$bulan}/{$tahun}/" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
