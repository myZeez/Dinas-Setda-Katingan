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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('page_visited')->default('/');
            $table->string('referer')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('device_type')->default('desktop'); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->date('visit_date');
            $table->timestamps();

            // Index for faster queries
            $table->index('visit_date');
            $table->index(['ip_address', 'visit_date']);
        });

        // Monthly stats summary for faster chart loading
        Schema::create('visitor_stats', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->integer('total_visits')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->timestamps();

            $table->unique(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_stats');
        Schema::dropIfExists('visitors');
    }
};
