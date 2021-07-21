<?php

use App\Http\Controllers\SearchFlightsController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'trips');

Route::get('trips', [TripController::class, 'index'])
    ->name('trips');

Route::get('trips/create', [TripController::class, 'create'])
    ->name('trips.create');

Route::post('trips', [TripController::class, 'store'])
    ->name('trips.store');

Route::get('trips/{trip}/edit', [TripController::class, 'edit'])
    ->name('trips.edit');

Route::delete('trips/{trip}', [TripController::class, 'destroy'])
    ->name('trips.destroy');

Route::get('search/flights', SearchFlightsController::class)
    ->name('flights.search');
