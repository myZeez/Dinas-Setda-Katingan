<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitKerja;
use App\Models\Pegawai;

class StrukturOrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        // Level 1: Sekretaris Daerah
        $sekda = UnitKerja::create([
            'nama' => 'Sekretaris Daerah',
            'singkatan' => 'SEKDA',
            'level' => 'sekda',
            'urutan' => 1,
        ]);

        // Level 2: Asisten I - Pemerintahan dan Kesejahteraan Rakyat
        $asisten1 = UnitKerja::create([
            'nama' => 'Asisten I Pemerintahan dan Kesejahteraan Rakyat',
            'singkatan' => 'ASISTEN I',
            'level' => 'asisten',
            'parent_id' => $sekda->id,
            'urutan' => 1,
        ]);

        // Level 2: Asisten II - Perekonomian dan Pembangunan
        $asisten2 = UnitKerja::create([
            'nama' => 'Asisten II Perekonomian dan Pembangunan',
            'singkatan' => 'ASISTEN II',
            'level' => 'asisten',
            'parent_id' => $sekda->id,
            'urutan' => 2,
        ]);

        // Level 2: Asisten III - Administrasi Umum
        $asisten3 = UnitKerja::create([
            'nama' => 'Asisten III Administrasi Umum',
            'singkatan' => 'ASISTEN III',
            'level' => 'asisten',
            'parent_id' => $sekda->id,
            'urutan' => 3,
        ]);

        // =====================
        // BAGIAN ASISTEN I
        // =====================

        // Bagian Pemerintahan
        $bagPemerintahan = UnitKerja::create([
            'nama' => 'Bagian Pemerintahan',
            'singkatan' => 'BAG. PEMERINTAHAN',
            'level' => 'bagian',
            'parent_id' => $asisten1->id,
            'urutan' => 1,
        ]);

        // Bagian Kesejahteraan Rakyat
        $bagKesra = UnitKerja::create([
            'nama' => 'Bagian Kesejahteraan Rakyat',
            'singkatan' => 'BAG. KESRA',
            'level' => 'bagian',
            'parent_id' => $asisten1->id,
            'urutan' => 2,
        ]);

        // Bagian Hukum
        $bagHukum = UnitKerja::create([
            'nama' => 'Bagian Hukum',
            'singkatan' => 'BAG. HUKUM',
            'level' => 'bagian',
            'parent_id' => $asisten1->id,
            'urutan' => 3,
        ]);

        // =====================
        // BAGIAN ASISTEN II
        // =====================

        // Bagian Perekonomian dan Sumber Daya Alam
        $bagPerekonomian = UnitKerja::create([
            'nama' => 'Bagian Perekonomian dan Sumber Daya Alam',
            'singkatan' => 'BAG. PEREKONOMIAN & SDA',
            'level' => 'bagian',
            'parent_id' => $asisten2->id,
            'urutan' => 1,
        ]);

        // Bagian Administrasi Pembangunan
        $bagAdminPembangunan = UnitKerja::create([
            'nama' => 'Bagian Administrasi Pembangunan',
            'singkatan' => 'BAG. ADMIN PEMBANGUNAN',
            'level' => 'bagian',
            'parent_id' => $asisten2->id,
            'urutan' => 2,
        ]);

        // Bagian Pengadaan Barang dan Jasa
        $bagPBJ = UnitKerja::create([
            'nama' => 'Bagian Pengadaan Barang dan Jasa',
            'singkatan' => 'BAG. PBJ',
            'level' => 'bagian',
            'parent_id' => $asisten2->id,
            'urutan' => 3,
        ]);

        // Sub Bagian PBJ
        UnitKerja::create([
            'nama' => 'Subbagian Pengeluaran Pengadaan Barang dan Jasa',
            'level' => 'subbagian',
            'parent_id' => $bagPBJ->id,
            'urutan' => 1,
        ]);

        UnitKerja::create([
            'nama' => 'Subbagian Pengelolaan Layanan Pengadaan Secara Elektronik',
            'level' => 'subbagian',
            'parent_id' => $bagPBJ->id,
            'urutan' => 2,
        ]);

        UnitKerja::create([
            'nama' => 'Subbagian Pembinaan dan Advokasi Pengadaan Barang dan Jasa',
            'level' => 'subbagian',
            'parent_id' => $bagPBJ->id,
            'urutan' => 3,
        ]);

        // =====================
        // BAGIAN ASISTEN III
        // =====================

        // Bagian Umum
        $bagUmum = UnitKerja::create([
            'nama' => 'Bagian Umum',
            'singkatan' => 'BAG. UMUM',
            'level' => 'bagian',
            'parent_id' => $asisten3->id,
            'urutan' => 1,
        ]);

        // Sub Bagian Umum
        UnitKerja::create([
            'nama' => 'Subbagian Tata Usaha Pimpinan, Staf Ahli dan Kepegawaian',
            'level' => 'subbagian',
            'parent_id' => $bagUmum->id,
            'urutan' => 1,
        ]);

        UnitKerja::create([
            'nama' => 'Subbagian Keuangan',
            'level' => 'subbagian',
            'parent_id' => $bagUmum->id,
            'urutan' => 2,
        ]);

        UnitKerja::create([
            'nama' => 'Subbagian Rumah Tangga dan Perlengkapan',
            'level' => 'subbagian',
            'parent_id' => $bagUmum->id,
            'urutan' => 3,
        ]);

        // Bagian Organisasi
        $bagOrganisasi = UnitKerja::create([
            'nama' => 'Bagian Organisasi',
            'singkatan' => 'BAG. ORGANISASI',
            'level' => 'bagian',
            'parent_id' => $asisten3->id,
            'urutan' => 2,
        ]);

        // Bagian Protokol dan Komunikasi Pimpinan
        $bagProtokol = UnitKerja::create([
            'nama' => 'Bagian Protokol dan Komunikasi Pimpinan',
            'singkatan' => 'BAG. PROTOKOL',
            'level' => 'bagian',
            'parent_id' => $asisten3->id,
            'urutan' => 3,
        ]);

        // Sub Bagian Protokol
        UnitKerja::create([
            'nama' => 'Subbagian Protokol',
            'level' => 'subbagian',
            'parent_id' => $bagProtokol->id,
            'urutan' => 1,
        ]);

        UnitKerja::create([
            'nama' => 'Subbagian Komunikasi Pimpinan',
            'level' => 'subbagian',
            'parent_id' => $bagProtokol->id,
            'urutan' => 2,
        ]);

        UnitKerja::create([
            'nama' => 'Subbagian Dokumentasi Pimpinan',
            'level' => 'subbagian',
            'parent_id' => $bagProtokol->id,
            'urutan' => 3,
        ]);

        // Bagian Perpustakaan dan Arsip
        $bagPerpustakaan = UnitKerja::create([
            'nama' => 'Bagian Perpustakaan dan Arsip',
            'singkatan' => 'BAG. PERPUSTAKAAN & ARSIP',
            'level' => 'bagian',
            'parent_id' => $asisten3->id,
            'urutan' => 4,
        ]);

        // =====================
        // CONTOH DATA PEGAWAI BAGIAN PEMERINTAHAN
        // =====================

        Pegawai::create([
            'nama' => 'EKA METRA, S.STP., M.A.P',
            'nip' => '198509052003122003',
            'jabatan' => 'Kepala Bagian',
            'unit_kerja_id' => $bagPemerintahan->id,
            'is_pimpinan' => true,
            'urutan' => 1,
        ]);

        Pegawai::create([
            'nama' => 'LURIE MARCIATE, SP., M.Si',
            'nip' => '198112032010012002',
            'jabatan' => 'Analis Kebijakan Ahli Muda',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 2,
        ]);

        Pegawai::create([
            'nama' => 'ASANUANA NARA, S.Psi',
            'nip' => '198905032015031006',
            'jabatan' => 'Analis Kebijakan Ahli Muda',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 3,
        ]);

        Pegawai::create([
            'nama' => 'DAVID ACHIANG, A.Md',
            'nip' => '198209202010011008',
            'jabatan' => 'Pengelolaan Data dan Informasi',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 4,
        ]);

        Pegawai::create([
            'nama' => 'AKHMAD QASTALANI, S.Tr.IP',
            'nip' => '200003132020081001',
            'jabatan' => 'Analis Desa dan Kelurahan',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 5,
        ]);

        Pegawai::create([
            'nama' => 'GEFRI BERLAND NASTOLIO, S.Tr.IP',
            'nip' => '200001302020081001',
            'jabatan' => 'Analis Pemerintahan Daerah',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 6,
        ]);

        Pegawai::create([
            'nama' => 'KERLITA, S.IP',
            'nip' => '199302092025042002',
            'jabatan' => 'Penata Kelola Pemerintahan',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 7,
        ]);

        Pegawai::create([
            'nama' => 'DHENA ALDHALIA, S.I.P',
            'nip' => '200101272025042001',
            'jabatan' => 'Analis Kebijakan Ahli Pertama',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 8,
        ]);

        Pegawai::create([
            'nama' => 'ALDO ARISANDY R.D, S.I.P',
            'nip' => '200003112025041001',
            'jabatan' => 'Penata Kelola Pemerintahan',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 9,
        ]);

        Pegawai::create([
            'nama' => 'WILLYE KRISTANTO',
            'nip' => '199103092022031002',
            'jabatan' => 'Pembah. Pemula - Pengatur Muda',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 10,
        ]);

        Pegawai::create([
            'nama' => 'DEBY HERMAWAN',
            'nip' => '199304182022031005',
            'jabatan' => 'Pembah. Pemula - Pengatur Muda',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 11,
        ]);

        Pegawai::create([
            'nama' => 'HERMANSYAH',
            'nip' => 'NIPPK. 199105102022011008',
            'jabatan' => 'Pengadministrasi Perkantoran',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 12,
        ]);

        Pegawai::create([
            'nama' => 'UJANG',
            'nip' => 'NIPPK. 197811242022012005',
            'jabatan' => 'Pengadministrasi Perkantoran',
            'unit_kerja_id' => $bagPemerintahan->id,
            'urutan' => 13,
        ]);
    }
}
