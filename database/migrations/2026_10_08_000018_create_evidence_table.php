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
        Schema::create('evidence', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('evidence_type', 50);
            $table->string('classification', 50)->default('STRONG_EVIDENCE');
            $table->text('description');
            $table->string('stratigraphic_context')->nullable();
            $table->string('methodology_applied')->nullable();
            $table->text('uncertainty_notes')->nullable();
            $table->timestamps();

            $table->index(['classification', 'evidence_type'], 'idx_evidence_class_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence');
    }
};
