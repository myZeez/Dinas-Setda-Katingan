<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Alur baru:
     * 1. User upload Surat Penawaran -> menunggu ACC admin
     * 2. Admin ACC -> User upload KAK + input link Nota Kesepakatan (Google Docs)
     * 3. Proses selesai
     */
    public function up(): void
    {
        // Update tabel pengajuan_layanans
        Schema::table('pengajuan_layanans', function (Blueprint $table) {
            // Status baru yang lebih sesuai dengan alur
            // Status: draft, menunggu_review_sp, sp_disetujui, sp_revisi, menunggu_dokumen_lanjutan, selesai, ditolak

            // Tambah kolom untuk file KAK dan link nota kesepakatan
            $table->string('file_kak')->nullable()->after('catatan_admin');
            $table->string('link_nota_kesepakatan')->nullable()->after('file_kak');
            $table->text('catatan_revisi_sp')->nullable()->after('link_nota_kesepakatan');
            $table->text('catatan_revisi_kak')->nullable()->after('catatan_revisi_sp');
        });

        // Update enum status di dokumen_layanans untuk menambah status 'disetujui' dan 'revisi'
        // Tidak perlu alter enum, gunakan string saja
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_layanans', function (Blueprint $table) {
            $table->dropColumn(['file_kak', 'link_nota_kesepakatan', 'catatan_revisi_sp', 'catatan_revisi_kak']);
        });
    }
};
