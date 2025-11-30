<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_layanan_id',
        'user_id',
        'status',
        'keterangan',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanLayanan::class, 'pengajuan_layanan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
