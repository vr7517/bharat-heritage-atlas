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
        Schema::create('objects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('heritage_site_id')->nullable()->constrained('heritage_sites')->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('period_id')->nullable()->constrained('periods')->nullOnDelete();
            $table->foreignId('dynasty_id')->nullable()->constrained('dynasties')->nullOnDelete();
            $table->string('object_type', 100);
            $table->string('material', 150);
            $table->string('dimensions')->nullable();
            $table->string('current_repository');
            $table->string('accession_number', 100)->nullable();
            $table->string('dating_statement');
            $table->integer('start_year')->nullable();
            $table->integer('end_year')->nullable();
            $table->boolean('is_dating_uncertain')->default(false);
            $table->text('description');
            $table->text('iconographic_notes')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index('object_type', 'idx_objects_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objects');
    }
};
