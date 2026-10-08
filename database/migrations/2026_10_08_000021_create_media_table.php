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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediable');
            $table->string('file_path', 500);
            $table->string('caption', 500);
            $table->string('alt_text');
            $table->string('media_type', 50)->default('PHOTOGRAPH');
            $table->string('license', 100);
            $table->string('source_credit');
            $table->string('photographer_or_draughtsman', 150)->nullable();
            $table->integer('capture_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
