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
        // Update pengajuan_layanans table
        Schema::table('pengajuan_layanans', function (Blueprint $table) {
            // File paths
            $table->string('surat_penawaran')->nullable()->after('catatan_admin');
            $table->string('kerangka_acuan_kerja')->nullable()->after('surat_penawaran');
            $table->string('nota_kesepakatan_link')->nullable()->after('kerangka_acuan_kerja'); // Google Docs link
            
            // Status per dokumen
            $table->enum('status_surat_penawaran', ['pending', 'disetujui', 'revisi'])->default('pending')->after('nota_kesepakatan_link');
            $table->text('catatan_surat_penawaran')->nullable()->after('status_surat_penawaran');
            
            $table->enum('status_kak', ['pending', 'disetujui', 'revisi'])->nullable()->after('catatan_surat_penawaran');
            $table->text('catatan_kak')->nullable()->after('status_kak');
            
            $table->enum('status_nota', ['pending', 'disetujui', 'revisi'])->nullable()->after('catatan_kak');
            $table->text('catatan_nota')->nullable()->after('status_nota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_layanans', function (Blueprint $table) {
            $table->dropColumn([
                'surat_penawaran',
                'kerangka_acuan_kerja',
                'nota_kesepakatan_link',
                'status_surat_penawaran',
                'catatan_surat_penawaran',
                'status_kak',
                'catatan_kak',
                'status_nota',
                'catatan_nota',
            ]);
        });
    }
};
