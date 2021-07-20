<?php

namespace App\Models;

class Flight extends Model
{
    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];
}
