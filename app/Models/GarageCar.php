<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarageCar extends Model
{
    // Allows mass-assignment for updating car attributes quickly
    protected $guarded = [];

    /**
     * The player/user who owns this custom vehicle build.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The baseline vehicle catalog model stats (make, model, base HP, etc.)
     */
    public function baseCar()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}