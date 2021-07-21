<?php

namespace App\Models;

class Flight extends Model
{
    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['departure'] ?? null, function ($query, $departure) {
            $query->where('departure_airport_id', $departure);
        })->when($filters['arrival'] ?? null, function ($query, $arrival) {
            $query->where('arrival_airport_id', $arrival);
        })->when($filters['datetime'] ?? null, function ($query, $datetime) {
            $query->where('departure_time', '>=', $datetime);
        });
    }
}
