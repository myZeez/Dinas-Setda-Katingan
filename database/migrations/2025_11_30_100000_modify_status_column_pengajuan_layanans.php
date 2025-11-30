<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom status dari enum ke varchar agar lebih fleksibel
        DB::statement("ALTER TABLE pengajuan_layanans MODIFY COLUMN status VARCHAR(50) DEFAULT 'menunggu_review_sp'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum (jika perlu rollback)
        DB::statement("ALTER TABLE pengajuan_layanans MODIFY COLUMN status ENUM('draft', 'diajukan', 'diproses', 'koreksi', 'proses_ttd', 'penjadwalan_ttd', 'selesai', 'ditolak') DEFAULT 'draft'");
    }
};
