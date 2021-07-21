<?php

namespace Database\Seeders;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Trip;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Airline::create([
            'code' => 'AC',
            'name' => 'Air Canada',
        ]);

        Airline::create([
            'code' => 'WN',
            'name' => 'Southwest Airlines',
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

        Airport::create([
            'code' => 'LAX',
            'city_code' => 'LAX',
            'name' => 'Los Angeles International Airport',
            'city' => 'Los Angeles',
            'country_code' => 'US',
            'region_code' => 'CA',
            'latitude' => 37.7007814,
            'longitude' => -116.4313936,
            'timezone' => 'America/Los_Angeles',
        ]);

        $faker = Faker::create();

        foreach (range(100, 500) as $number) {
            $airports = Airport::inRandomOrder()->get();

            Flight::create([
                'airline_id' => Airline::inRandomOrder()->first()->id,
                'number' => $number,
                'departure_airport_id' => $airports->first()->id,
                'departure_time' => $departureTime = $faker->dateTimeBetween('now', '1 year'),
                'arrival_airport_id' => $airports->last()->id,
                'arrival_time' => Carbon::instance($departureTime)->addHours(5),
                'price' => $faker->numberBetween(100, 500),
            ]);
        }

        $trip = Trip::create([
            'type' => Trip::ONE_WAY,
        ]);

        $trip->flights()->attach(Flight::first());
    }
}
