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
        Schema::create('claim_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims')->cascadeOnDelete();
            $table->foreignId('evidence_id')->constrained('evidence')->cascadeOnDelete();
            $table->string('relationship_type', 50)->default('SUPPORTS');
            $table->string('scholarly_weight', 50)->default('PRIMARY');
            $table->text('analysis_notes')->nullable();
            $table->timestamps();

            $table->unique(['claim_id', 'evidence_id', 'relationship_type'], 'unq_claim_evidence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_evidence');
    }
};
