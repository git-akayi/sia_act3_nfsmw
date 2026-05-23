<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'master_cars';

    protected $fillable = [
        'make_model',
        'base_hp',
        'base_torque',
        'base_market_value',
        'tier',
        'car_image_path',
        'engine_type',
        'top_speed',
        'transmission',
        'weight_kg',
    ];
}