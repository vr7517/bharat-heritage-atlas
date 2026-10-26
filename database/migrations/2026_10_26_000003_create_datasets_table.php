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
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('source_organization');
            $table->string('source_url')->nullable();
            $table->string('dataset_type', 50)->default('CSV');
            $table->string('version', 50)->default('1.0');
            $table->date('release_date')->nullable();
            $table->string('license')->default('Creative Commons CC0 1.0 (Public Domain)');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size_bytes')->nullable();
            $table->string('file_mime_type')->nullable();
            $table->unsignedInteger('record_count')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['dataset_type', 'is_published']);
            $table->index(['is_featured', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};
