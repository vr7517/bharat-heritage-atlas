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
        Schema::create('heritage_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('primary_period_id')->nullable()->constrained('periods')->nullOnDelete();
            $table->foreignId('primary_dynasty_id')->nullable()->constrained('dynasties')->nullOnDelete();
            $table->string('site_type', 100);
            $table->string('dating_statement');
            $table->integer('start_year')->nullable();
            $table->integer('end_year')->nullable();
            $table->boolean('is_dating_uncertain')->default(false);
            $table->string('protection_status', 150);
            $table->text('summary');
            $table->longText('historical_context')->nullable();
            $table->longText('architectural_description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index('site_type', 'idx_sites_site_type');
            $table->index(['start_year', 'end_year'], 'idx_sites_start_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heritage_sites');
    }
};
