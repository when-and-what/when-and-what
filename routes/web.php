<?php

use App\Http\Controllers\Locations\CheckinController;
use App\Http\Controllers\Locations\LocationController;
use App\Http\Controllers\Locations\PendingCheckinController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth'])->group(function(){
    Route::get('checkins/pending/{checkin}', [PendingCheckinController::class, 'show']);
    Route::resource('locations', LocationController::class);
    Route::resource('checkins', CheckinController::class);
});
