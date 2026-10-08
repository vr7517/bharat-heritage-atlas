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
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('heritage_site_id')->nullable()->constrained('heritage_sites')->nullOnDelete();
            $table->foreignId('object_id')->nullable()->constrained('objects')->nullOnDelete();
            $table->string('language', 100);
            $table->string('script', 100);
            $table->string('dating_statement');
            $table->integer('start_year')->nullable();
            $table->integer('end_year')->nullable();
            $table->string('donor')->nullable();
            $table->string('ruler_mentioned')->nullable();
            $table->string('epigraphic_reference')->nullable();
            $table->text('raw_text')->nullable();
            $table->text('translation');
            $table->text('interpretation_notes')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
