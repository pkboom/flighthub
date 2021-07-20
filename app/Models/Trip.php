<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    public const ONE_WAY = 'one_way';
    public const ROUND_TRIP = 'round_trip';

    public function flights()
    {
        return $this->belongsToMany(Flight::class);
    }
}
