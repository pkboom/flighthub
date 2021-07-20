<?php

namespace Database\Seeders;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Airline::create([
            'code' => 'AC',
            'name' => 'Air Canada',
        ]);

        Airport::create([
            'code' => 'YUL',
            'city_code' => 'YMQ',
            'name' => 'Pierre Elliott Trudeau International',
            'city' => 'Montreal',
            'country_code' => 'CA',
            'region_code' => 'QC',
            'latitude' => 45.457714,
            'longitude' => -73.749908,
            'timezone' => 'America/Montreal',
        ]);

        Airport::create([
            'code' => 'YVR',
            'city_code' => 'YVR',
            'name' => 'Vancouver International',
            'city' => 'Vancouver',
            'country_code' => 'CA',
            'region_code' => 'BC',
            'latitude' => 49.194698,
            'longitude' => -123.179192,
            'timezone' => 'America/Vancouver',
        ]);

        Flight::create([
            'airline_id' => Airline::first()->id,
            'number' => '301',
            'departure_airport_id' => Airport::first()->id,
            'departure_time' => '07:35',
            'arrival_airport_id' => Airport::offset(1)->first()->id,
            'arrival_time' => '10:05',
            'price' => '273.23',
        ]);

        Flight::create([
            'airline_id' => Airline::first()->id,
            'number' => '302',
            'departure_airport_id' => Airport::offset(1)->first()->id,
            'departure_time' => '11:30',
            'arrival_airport_id' => Airport::first()->id,
            'arrival_time' => '19:11',
            'price' => '220.63',
        ]);

        $trip = Trip::create([
            'type' => Trip::ONE_WAY,
        ]);

        $trip->flights()->attach([
            'flight_id' => Flight::first()->id,
        ]);
    }
}
