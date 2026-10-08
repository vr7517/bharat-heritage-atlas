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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->morphs('claimable');
            $table->string('claim_type', 50);
            $table->text('statement');
            $table->string('consensus_status', 50)->default('STRONG_CONSENSUS');
            $table->text('summary_justification')->nullable();
            $table->timestamps();

            $table->index(['claim_type', 'consensus_status'], 'idx_claims_type_consensus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
