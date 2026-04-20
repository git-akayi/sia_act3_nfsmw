<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  protected $fillable = [
    'name', 'alias', 'car', 'strength', 'territory', 'blacklist_rank', 'bounty'
];
}
