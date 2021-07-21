<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use App\Models\Trip;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SearchFlightsController extends Controller
{
    public function __invoke()
    {
        Request::validate([
            'departure' => ['required', 'exists:airports,id'],
            'arrival' => ['required', 'exists:airports,id'],
            'datetime' => ['required', 'date', 'after_or_equal:now', 'before_or_equal:365 day'],
            'type' => ['required', 'in:one_way,round_trip'],
        ]);

        return Inertia::render('Trips/Create', [
            'filters' => Request::all('departure', 'arrival', 'datetime'),
            'airports' => Airport::all(),
            'leaving' => Flight::query()
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
                ]),
            'returning' => Request::input('type') === Trip::ROUND_TRIP ? Flight::query()
                ->with('departureAirport', 'arrivalAirport', 'airline')
                ->filter([
                    'departure' => Request::input('arrival'),
                    'arrival' => Request::input('departure'),
                    'datetime' => Request::input('datetime'),
                ])
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
}
