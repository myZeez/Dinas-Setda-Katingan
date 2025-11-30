<?php

namespace Database\Seeders;

use App\Models\JenisLayanan;
use Illuminate\Database\Seeder;

class JenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanan = [
            [
                'kode' => 'KSDD',
                'nama' => 'Kerja Sama Daerah dengan Daerah Lain',
                'deskripsi' => 'Layanan pengajuan kerja sama antara Pemerintah Daerah Kabupaten Katingan dengan Pemerintah Daerah lainnya.',
                'icon' => 'bi-building',
                'is_active' => true,
            ],
            [
                'kode' => 'KSDPK',
                'nama' => 'Kerja Sama Daerah dengan Pihak Ketiga',
                'deskripsi' => 'Layanan pengajuan kerja sama antara Pemerintah Daerah Kabupaten Katingan dengan Pihak Ketiga (Swasta/Lembaga).',
                'icon' => 'bi-briefcase',
                'is_active' => true,
            ],
            [
                'kode' => 'NOTA',
                'nama' => 'Nota Kesepakatan',
                'deskripsi' => 'Layanan pengajuan Nota Kesepakatan dengan pihak terkait.',
                'icon' => 'bi-file-earmark-text',
                'is_active' => true,
            ],
        ];

        foreach ($layanan as $item) {
            JenisLayanan::create($item);
        }
    }
}
