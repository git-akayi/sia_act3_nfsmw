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
        Schema::create('garage_cars', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key linking back to the logged-in user
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Explicitly linking car_id to the 'master_cars' table reference index
            $table->foreignId('car_id')->constrained('master_cars')->onDelete('cascade');
            
            // Dynamic instances variables for this specific purchase item
            $table->integer('current_hp');
            $table->integer('current_torque');
            $table->integer('calculated_valuation');
            $table->integer('mechanical_efficiency')->default(100); // Percentage condition wear state
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garage_cars');
    }
};