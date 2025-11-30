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
        // Tabel Carousel/Slider
        Schema::create('carousels', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('gambar');
            $table->string('link')->nullable();
            $table->string('tombol_text')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Video YouTube
        Schema::create('video_youtubes', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('youtube_id'); // ID video YouTube (misal: dQw4w9WgXcQ)
            $table->string('thumbnail')->nullable(); // Custom thumbnail atau otomatis dari YouTube
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Pemimpin Daerah
        Schema::create('pemimpin_daerahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('periode')->nullable(); // Misal: 2024-2029
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemimpin_daerahs');
        Schema::dropIfExists('video_youtubes');
        Schema::dropIfExists('carousels');
    }
};
