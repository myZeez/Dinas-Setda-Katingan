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
        // Update tabel dokumen_layanans untuk mendukung alur bertahap
        Schema::table('dokumen_layanans', function (Blueprint $table) {
            // Tambah kolom untuk link (nota kesepakatan pakai Google Docs)
            $table->string('link_dokumen')->nullable()->after('file_path');
            // Tambah kolom catatan revisi dari admin
            $table->text('catatan_revisi')->nullable()->after('catatan');
            // Tambah timestamp kapan dokumen disetujui
            $table->timestamp('approved_at')->nullable()->after('versi');
            // Tambah user_id admin yang mereview
            $table->foreignId('reviewed_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
        });

        // Update jenis dokumen enum untuk mendukung nota_kesepakatan
        // Kita perlu recreate kolom karena MySQL tidak support ALTER ENUM dengan mudah
        Schema::table('dokumen_layanans', function (Blueprint $table) {
            $table->string('jenis_dokumen_new')->default('surat_penawaran')->after('jenis_dokumen');
        });

        // Copy data
        DB::statement("UPDATE dokumen_layanans SET jenis_dokumen_new = jenis_dokumen");

        // Drop old column and rename new
        Schema::table('dokumen_layanans', function (Blueprint $table) {
            $table->dropColumn('jenis_dokumen');
        });

        Schema::table('dokumen_layanans', function (Blueprint $table) {
            $table->renameColumn('jenis_dokumen_new', 'jenis_dokumen');
        });

        // Update status enum juga
        Schema::table('dokumen_layanans', function (Blueprint $table) {
            $table->string('status_new')->default('menunggu')->after('status');
        });

        DB::statement("UPDATE dokumen_layanans SET status_new = status");

        Schema::table('dokumen_layanans', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('dokumen_layanans', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });

        // Update pengajuan_layanans untuk tracking tahap saat ini
        Schema::table('pengajuan_layanans', function (Blueprint $table) {
            $table->string('tahap_aktif')->default('surat_penawaran')->after('status');
            // surat_penawaran -> kerangka_acuan_kerja -> nota_kesepakatan -> selesai
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_layanans', function (Blueprint $table) {
            $table->dropColumn('tahap_aktif');
        });

        Schema::table('dokumen_layanans', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['link_dokumen', 'catatan_revisi', 'approved_at', 'reviewed_by']);
        });
    }
};
