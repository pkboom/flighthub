<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::redirect('/', 'trips');

Route::get('trips', [TripController::class, 'index'])
    ->name('trips');

Route::get('trips/create', [TripController::class, 'create'])
    ->name('trips.create');

Route::post('trips', [TripController::class, 'store'])
    ->name('trips.store');

Route::get('trips/{trip}/edit', [TripController::class, 'edit'])
    ->name('trips.edit');

Route::put('trips/{trip}', [TripController::class, 'update'])
    ->name('trips.update');

// Route::delete('trips/{trip}', [TripController::class, 'destroy'])
//     ->name('trips.destroy');
