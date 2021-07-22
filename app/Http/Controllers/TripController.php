<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Trip;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class TripController extends Controller
{
    public function index()
    {
        return Inertia::render('Trips/Index', [
            'trips' => Trip::query()
                ->with(['flights' => function ($query) {
                    $query->with(['departureAirport', 'arrivalAirport']);
                }])
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($trip) => [
                    'id' => $trip->id,
                    'type' => $trip->type,
                    'departure_location' => ($departureAirport = $trip->flights->sortBy('departure_time')->first()->departureAirport)->name,
                    'departure_time' => $trip->flights->sortBy('departure_time')->first()->departure_time->setTimezone($departureAirport->timezone)->format('Y-m-d H:i:m'),
                    'arrival_location' => $trip->flights->sortBy('arrival_time')->first()->arrivalAirport->name,
                    'price' => $trip->flights->sum('price'),
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Trips/Create', [
            'filters' => Request::all('departure', 'arrival', 'datetime'),
            'airports' => Airport::all(),
            'leaving' => [
                'data' => [],
                'links' => [],
            ],
            'returning' => [
                'data' => [],
                'links' => [],
            ],
        ]);
    }

    public function store()
    {
        $trip = Trip::create([
            'type' => Request::input('type'),
        ]);

        $trip->flights()->attach(collect(Request::input('bookings'))->pluck('id'));

        return Redirect::route('trips.show', $trip)->with('success', 'Trip created.');
    }

    public function show(Trip $trip)
    {
        $trip->load(['flights' => function ($query) {
            $query->with('departureAirport', 'arrivalAirport', 'airline')
                ->orderBy('departure_time');
        }]);

        return Inertia::render('Trips/Edit', [
            'trip' => [
                'id' => $trip->id,
                'type' => $trip->type,
                'departure_location' => ($departureAirport = $trip->flights->sortBy('departure_time')->first()->departureAirport)->name,
                'departure_time' => $trip->flights->sortBy('departure_time')->first()->departure_time->setTimezone($departureAirport->timezone)->format('Y-m-d H:i:m'),
                'arrival_location' => $trip->flights->sortByDesc('arrival_time')->first()->arrivalAirport->name,
                'price' => $trip->flights->sum('price'),
                'flights' => $trip->flights->map(function ($flight) {
                    return [
                        'airline_code' => $flight->airline->code,
                        'airline_name' => $flight->airline->name,
                        'number' => $flight->number,
                        'price' => $flight->price,
                        'departure' => ($departureAirport = $flight->departureAirport)->name,
                        'departure_time' => $flight->departure_time->setTimezone($departureAirport->timezone)->format('Y-m-d H:i:m'),
                        'arrival' => ($arrivalAirport = $flight->arrivalAirport)->name,
                        'arrival_time' => $flight->arrival_time->setTimezone($arrivalAirport->timezone)->format('Y-m-d H:i:m'),
                    ];
                }),
            ],
        ]);
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();

        $trip->flights()->detach();

        return Redirect::route('trips')->with('success', 'Trip deleted.');
    }
}
