<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriInformasi;
use App\Models\JenisDokumen;

class InformasiPublikSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori 1: Informasi Publik Bagian Pemerintahan
        $infoPemerintahan = KategoriInformasi::create([
            'nama' => 'Informasi Publik Bagian Pemerintahan',
            'slug' => 'informasi-publik-bagian-pemerintahan',
            'icon' => 'bi-file-earmark-text',
            'deskripsi' => 'Dokumen dan informasi publik dari Bagian Pemerintahan',
            'urutan' => 1,
        ]);

        // Jenis Dokumen untuk Informasi Publik Bag. Pemerintahan
        $jenisDokumenPemerintahan = [
            'LKPJ (Laporan Penyelenggaraan Pemerintah Daerah)',
            'SPM (Standar Pelayanan Minimal)',
            'Keputusan Bupati',
            'Peraturan Bupati',
            'Peraturan Daerah',
            'Laporan Kinerja',
        ];

        foreach ($jenisDokumenPemerintahan as $index => $nama) {
            JenisDokumen::create([
                'nama' => $nama,
                'slug' => \Illuminate\Support\Str::slug($nama),
                'kategori_informasi_id' => $infoPemerintahan->id,
                'urutan' => $index + 1,
            ]);
        }

        // Kategori 2: Informasi Kewilayahan
        $infoKewilayahan = KategoriInformasi::create([
            'nama' => 'Informasi Kewilayahan',
            'slug' => 'informasi-kewilayahan',
            'icon' => 'bi-geo-alt',
            'deskripsi' => 'Informasi terkait batas wilayah desa, kecamatan, dan kabupaten',
            'urutan' => 2,
        ]);

        // Jenis Dokumen untuk Informasi Kewilayahan
        $jenisDokumenKewilayahan = [
            'Perbup Batas Desa',
            'Perbup Batas Kecamatan',
            'Perbup Batas Kabupaten',
            'Peta Wilayah',
        ];

        foreach ($jenisDokumenKewilayahan as $index => $nama) {
            JenisDokumen::create([
                'nama' => $nama,
                'slug' => \Illuminate\Support\Str::slug($nama),
                'kategori_informasi_id' => $infoKewilayahan->id,
                'urutan' => $index + 1,
            ]);
        }

        // Kategori 3: Informasi Kerja Sama
        $infoKerjaSama = KategoriInformasi::create([
            'nama' => 'Informasi Kerja Sama',
            'slug' => 'informasi-kerja-sama',
            'icon' => 'bi-handshake',
            'deskripsi' => 'Dokumen kerja sama antar pemerintah daerah dan instansi',
            'urutan' => 3,
        ]);

        // Jenis Dokumen untuk Informasi Kerja Sama
        $jenisDokumenKerjaSama = [
            'Nota Kesepakatan (MoU)',
            'Perjanjian Kerja Sama (PKS)',
            'Kesepakatan Bersama',
        ];

        foreach ($jenisDokumenKerjaSama as $index => $nama) {
            JenisDokumen::create([
                'nama' => $nama,
                'slug' => \Illuminate\Support\Str::slug($nama),
                'kategori_informasi_id' => $infoKerjaSama->id,
                'urutan' => $index + 1,
            ]);
        }
    }
}
