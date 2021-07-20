<?php

namespace App\Http\Controllers;

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
            'filters' => Request::all('search', 'trashed'),
            'trips' => Trip::with('flights')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($trip) => [
                    'id' => $trip->id,
                    'name' => $trip->name,
                    'phone' => $trip->phone,
                    'city' => $trip->city,
                    'deleted_at' => $trip->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Trips/Create');
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
        return Inertia::render('Trips/Edit', [
            'trip' => [
                'id' => $trip->id,
                'name' => $trip->name,
                'email' => $trip->email,
                'phone' => $trip->phone,
                'address' => $trip->address,
                'city' => $trip->city,
                'region' => $trip->region,
                'country' => $trip->country,
                'postal_code' => $trip->postal_code,
                'deleted_at' => $trip->deleted_at,
                'contacts' => $trip->contacts()->orderByName()->get()->map->only('id', 'name', 'city', 'phone'),
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
