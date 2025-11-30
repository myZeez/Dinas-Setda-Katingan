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
        Schema::create('contoh_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->text('keterangan')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->bigInteger('file_size')->default(0);
            $table->foreignId('jenis_layanan_id')->nullable()->constrained('jenis_layanans')->onDelete('set null');
            $table->integer('urutan')->default(0);
            $table->integer('jumlah_dilihat')->default(0);
            $table->integer('jumlah_diunduh')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contoh_dokumen');
    }
};
