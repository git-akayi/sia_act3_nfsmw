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
        Schema::table('users', function (Blueprint $table) {
            // Added once here with your avatar image fallback
            $table->string('avatar')->default('nfsmw.jpg')->after('password');
            $table->integer('blacklist_rank')->default(15);
            $table->bigInteger('bounty')->default(0);
            $table->integer('cars_owned')->default(0);
            $table->integer('rivals_left')->default(15);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'blacklist_rank', 'bounty', 'cars_owned', 'rivals_left']);
        });
    }
};