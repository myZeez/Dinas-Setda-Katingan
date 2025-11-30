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
        // Tabel Unit Kerja (Bagian/Subbagian)
        Schema::create('unit_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('singkatan')->nullable();
            $table->string('level'); // sekda, asisten, bagian, subbagian
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('unit_kerjas')->onDelete('set null');
        });

        // Tabel Pegawai
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('jabatan');
            $table->string('golongan')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedBigInteger('unit_kerja_id')->nullable();
            $table->boolean('is_pimpinan')->default(false); // Kepala unit
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('unit_kerja_id')->references('id')->on('unit_kerjas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
        Schema::dropIfExists('unit_kerjas');
    }
};
