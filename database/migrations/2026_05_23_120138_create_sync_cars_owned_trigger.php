<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Trigger for when a user buys a car (INSERT)
        DB::unprepared('
            CREATE TRIGGER after_garage_car_insert
            AFTER INSERT ON garage_cars
            FOR EACH ROW
            BEGIN
                UPDATE users 
                SET cars_owned = (SELECT COUNT(*) FROM garage_cars WHERE user_id = NEW.user_id)
                WHERE id = NEW.user_id;
            END
        ');

        // 2. Trigger for when a user sells/scraps a car (DELETE)
        DB::unprepared('
            CREATE TRIGGER after_garage_car_delete
            AFTER DELETE ON garage_cars
            FOR EACH ROW
            BEGIN
                UPDATE users 
                SET cars_owned = (SELECT COUNT(*) FROM garage_cars WHERE user_id = OLD.user_id)
                WHERE id = OLD.user_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_garage_car_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_garage_car_delete');
    }
};