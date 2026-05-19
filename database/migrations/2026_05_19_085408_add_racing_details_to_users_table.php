<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->string('signature_car')->default('BMW M3 GTR');
            $blueprint->string('territory')->default('Rosewood');
            $blueprint->string('race_specialty')->default('Sprint'); // ADDED THIS
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['signature_car', 'territory', 'race_specialty']);
        });
    }
};