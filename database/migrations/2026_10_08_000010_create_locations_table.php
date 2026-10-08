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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('historical_names')->nullable();
            $table->string('state', 100);
            $table->string('district', 100);
            $table->string('taluk_tehsil', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('altitude_meters')->nullable();
            $table->integer('uncertainty_radius_meters')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['state', 'district'], 'idx_locations_state_district');
            $table->index(['latitude', 'longitude'], 'idx_locations_lat_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
