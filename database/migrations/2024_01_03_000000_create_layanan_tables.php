<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel Jenis Layanan
        Schema::create('jenis_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // KSDD, KSDPK, NOTA
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Pengajuan Layanan
        Schema::create('pengajuan_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengajuan')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanans')->onDelete('cascade');
            $table->string('nama_pihak'); // Nama Daerah Lain / Pihak Ketiga
            $table->text('tentang'); // Tentang apa kerja sama
            $table->text('instansi_terkait')->nullable(); // Instansi yang ingin diajak
            $table->enum('status', [
                'draft',
                'diajukan',
                'diproses',
                'koreksi',
                'proses_ttd',
                'penjadwalan_ttd',
                'selesai',
                'ditolak'
            ])->default('draft');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });

        // Tabel Dokumen Layanan
        Schema::create('dokumen_layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_layanan_id')->constrained('pengajuan_layanans')->onDelete('cascade');
            $table->enum('jenis_dokumen', [
                'surat_penawaran',
                'kerangka_acuan_kerja',
                'draft_naskah'
            ]);
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('file_type'); // pdf, doc, docx
            $table->integer('file_size')->nullable();
            $table->enum('status', [
                'diterima',
                'diproses',
                'koreksi',
                'ditolak'
            ])->default('diproses');
            $table->text('catatan')->nullable();
            $table->integer('versi')->default(1);
            $table->timestamps();
        });

        // Tabel Log/History Pengajuan
        Schema::create('log_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_layanan_id')->constrained('pengajuan_layanans')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_pengajuans');
        Schema::dropIfExists('dokumen_layanans');
        Schema::dropIfExists('pengajuan_layanans');
        Schema::dropIfExists('jenis_layanans');
    }
};
