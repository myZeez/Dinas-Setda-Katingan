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
        Schema::table('users', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->after('name');
            $table->string('nip')->nullable()->after('jabatan');
            $table->string('nik')->nullable()->after('nip');
            $table->string('no_whatsapp')->nullable()->after('email');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('no_whatsapp');
            $table->string('instansi')->nullable()->after('jenis_kelamin');
            $table->string('biro_bagian')->nullable()->after('instansi');
            $table->enum('role', ['admin', 'user'])->default('user')->after('biro_bagian');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'jabatan',
                'nip',
                'nik',
                'no_whatsapp',
                'jenis_kelamin',
                'instansi',
                'biro_bagian',
                'role',
                'is_active'
            ]);
        });
    }
};
