<?php

use App\Http\Controllers\DayController;
use App\Http\Controllers\Locations\CategoriesController;
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
    return redirect('/dashboard');
});

Route::middleware(['auth:sanctum, verified'])->group(function(){
    Route::get('/dashboard', [DayController::class, 'index'])->name('dashboard');
    Route::get('/day/{year}/{month}/{day}', [DayController::class, 'day'])->name('day')->whereNumber(['year', 'month', 'day']);

    Route::resource('locations/checkins/pending', PendingCheckinController::class);
    Route::get('locations/checkins/create/{location?}', [CheckinController::class, 'create'])->name('checkins.create');
    Route::resource('locations/checkins', CheckinController::class)->except('create');
    Route::resource('locations/categories', CategoriesController::class);
    Route::resource('locations', LocationController::class);
});

// Sancum API Routes
Route::prefix('json')->group(function(){
    Route::resource('locations/checkins/pending', App\Http\Controllers\Api\Locations\PendingCheckinController::class, ['as' => 'json.checkins.pending']);
    Route::resource('locations/checkins', App\Http\Controllers\Api\Locations\CheckinController::class, ['as' => 'json.checkins']);
    Route::resource('locations', App\Http\Controllers\Api\Locations\LocationController::class, ['as' => 'json.locations']);
});
