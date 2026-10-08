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
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('dating_statement');
            $table->integer('start_year');
            $table->integer('end_year');
            $table->string('start_era', 10)->default('BCE');
            $table->string('end_era', 10)->default('BCE');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['start_year', 'end_year'], 'idx_periods_start_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};
