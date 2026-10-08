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
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->string('authors', 500);
            $table->integer('publication_year')->nullable();
            $table->string('source_type', 50);
            $table->string('publisher')->nullable();
            $table->string('journal_or_series')->nullable();
            $table->string('volume_issue', 100)->nullable();
            $table->string('pages', 100)->nullable();
            $table->string('doi', 150)->nullable();
            $table->string('isbn_issn', 100)->nullable();
            $table->string('url', 500)->nullable();
            $table->date('access_date')->nullable();
            $table->string('archival_location')->nullable();
            $table->string('reliability_tier', 50)->default('TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('authors', 'idx_sources_authors');
            $table->index(['source_type', 'reliability_tier'], 'idx_sources_type_tier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
