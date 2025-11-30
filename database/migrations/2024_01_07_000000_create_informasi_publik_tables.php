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
        // Tabel Kategori Informasi
        Schema::create('kategori_informasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Jenis Dokumen (untuk filter)
        Schema::create('jenis_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('kategori_informasi_id');
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('kategori_informasi_id')->references('id')->on('kategori_informasis')->onDelete('cascade');
        });

        // Tabel Informasi Publik
        Schema::create('informasi_publiks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nomor')->nullable(); // Nomor dokumen
            $table->date('tanggal')->nullable(); // Tanggal dokumen
            $table->text('keterangan')->nullable();
            $table->string('file_dokumen')->nullable(); // PDF utama
            $table->string('file_lampiran')->nullable(); // Lampiran (misal peta JPG)
            $table->string('lampiran_label')->nullable(); // Label lampiran
            $table->unsignedBigInteger('kategori_informasi_id');
            $table->unsignedBigInteger('jenis_dokumen_id')->nullable();
            $table->string('status')->default('berlaku'); // berlaku, tidak_berlaku, terealisasi
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('kategori_informasi_id')->references('id')->on('kategori_informasis')->onDelete('cascade');
            $table->foreign('jenis_dokumen_id')->references('id')->on('jenis_dokumens')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi_publiks');
        Schema::dropIfExists('jenis_dokumens');
        Schema::dropIfExists('kategori_informasis');
    }
};
