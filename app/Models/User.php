<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'cash',
        'blacklist_rank',
        'bounty',
        'cars_owned',
        'rivals_left',
        'signature_car',
        'territory',
        'race_specialty'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function garageCars()
    {
        return $this->hasMany(GarageCar::class);
    }
    public function getBlacklistRankFromBounty(): int
    {
        $thresholds = [
            15 => 0,
            14 => 50000,
            13 => 100000,
            12 => 180000,
            11 => 300000,
            10 => 500000,
            9  => 740000,
            8  => 1000000,
            7  => 1350000,
            6  => 1800000,
            5  => 2300000,
            4  => 3000000,
            3  => 4500000,
            2  => 6500000,
            1  => 10000000,
        ];

        foreach ($thresholds as $rank => $required) {
            if ($this->bounty >= $required) {
                return $rank;
            }
        }

        return 15;
    }
}
