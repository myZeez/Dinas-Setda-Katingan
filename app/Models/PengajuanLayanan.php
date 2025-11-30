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
        'file_kak',
        'link_nota_kesepakatan',
        'catatan_revisi_sp',
        'catatan_revisi_kak',
        'tanggal_pengajuan',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    // Status Labels - Alur Bertahap
    const STATUS_LABELS = [
        'draft' => 'Draft',
        'menunggu_review_sp' => 'Menunggu Review Surat Penawaran',
        'sp_disetujui' => 'Surat Penawaran Disetujui - Upload KAK',
        'sp_revisi' => 'Surat Penawaran Perlu Revisi',
        'menunggu_review_kak' => 'Menunggu Review KAK',
        'kak_disetujui' => 'KAK Disetujui - Upload Nota Kesepakatan',
        'kak_revisi' => 'KAK Perlu Revisi',
        'dokumen_lengkap' => 'Dokumen Lengkap',
        'diproses' => 'Sedang Diproses',
        'koreksi' => 'Perlu Koreksi',
        'proses_ttd' => 'Proses Tanda Tangan',
        'penjadwalan_ttd' => 'Penjadwalan TTD',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak',
    ];

    // Status Colors for Badge
    const STATUS_COLORS = [
        'draft' => 'secondary',
        'menunggu_review_sp' => 'info',
        'sp_disetujui' => 'success',
        'sp_revisi' => 'warning',
        'menunggu_review_kak' => 'info',
        'kak_disetujui' => 'success',
        'kak_revisi' => 'warning',
        'dokumen_lengkap' => 'primary',
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
