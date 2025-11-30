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
        Schema::table('pemimpin_daerahs', function (Blueprint $table) {
            $table->integer('grid_position')->nullable()->after('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemimpin_daerahs', function (Blueprint $table) {
            $table->dropColumn('grid_position');
        });
    }
};
