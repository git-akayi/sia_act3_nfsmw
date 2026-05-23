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
        Schema::create('master_cars', function (Blueprint $table) {
            $table->id();
            $table->string('make_model');
            $table->integer('base_hp');
            $table->integer('base_torque');
            $table->integer('base_market_value');
            $table->string('tier', 10)->nullable();
            $table->string('car_image_path')->nullable();
            $table->string('engine_type')->nullable();      // e.g. "4.2L V8"
            $table->integer('top_speed')->nullable();       // e.g. 190 (mph)
            $table->string('transmission')->nullable();     // e.g. "6-speed Manual"
            $table->integer('weight_kg')->nullable();       // e.g. 1400
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_cars');
    }
};