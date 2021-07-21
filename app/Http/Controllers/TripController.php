<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
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
            'flights' => Request::input('departure') ? Flight::query()
                ->with('departureAirport', 'arrivalAirport', 'airline')
                ->filter(Request::only('departure', 'arrival', 'datetime'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($flight) => [
                    'id' => $flight->id,
                    'airline_code' => $flight->airline->code,
                    'airline_name' => $flight->airline->name,
                    'number' => $flight->number,
                    'price' => $flight->price,
                    'departure' => ($departureAirport = $flight->departureAirport)->name,
                    'departure_time' => $flight->departure_time->setTimezone($departureAirport->timezone)->format('Y-m-d H:i:m'),
                    'arrival' => ($arrivalAirport = $flight->arrivalAirport)->name,
                    'arrival_time' => $flight->arrival_time->setTimezone($arrivalAirport->timezone)->format('Y-m-d H:i:m'),
                ]) : [
                    'data' => [],
                    'links' => [],
                ],
        ]);
    }

    public function store()
    {
        Auth::user()->account->trips()->create(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('trips')->with('success', 'Trip created.');
    }

    public function edit(Trip $trip)
    {
        $trip->load(['flights' => function ($query) {
            $query->with('departureAirport', 'arrivalAirport', 'airline');
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

    public function update(Trip $trip)
    {
        $trip->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Trip updated.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();

        return Redirect::back()->with('success', 'Trip deleted.');
    }

    public function restore(Trip $trip)
    {
        $trip->restore();

        return Redirect::back()->with('success', 'Trip restored.');
    }
}
