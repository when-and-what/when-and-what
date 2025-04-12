<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\Locations\CategoriesController;
use App\Http\Controllers\Locations\CheckinController;
use App\Http\Controllers\Locations\LocationController;
use App\Http\Controllers\Locations\LocationSearchController;
use App\Http\Controllers\Locations\PendingCheckinController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Podcasts\EpisodeController;
use App\Http\Controllers\Podcasts\EpisodeRatingController;
use App\Http\Controllers\Podcasts\PlaysController;
use App\Http\Controllers\Podcasts\PodcastController;
use App\Http\Controllers\TagController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DayController::class, 'index'])->name('dashboard');
    Route::get('/day/{year}/{month}/{day}', [DayController::class, 'day'])->name('day')->whereNumber(['year', 'month', 'day']);

    // Locations
    Route::resource('locations/checkins/pending', PendingCheckinController::class);
    Route::get('locations/checkins/create/{location?}', [CheckinController::class, 'create'])->name('checkins.create');
    Route::resource('locations/checkins', CheckinController::class)->except('create');
    Route::resource('locations/categories', CategoriesController::class);
    Route::post('locations/search', LocationSearchController::class)->name('locations.search');
    Route::get('locations/search', function () {
        return redirect('/locations');
    });
    Route::resource('locations', LocationController::class);

    // Podcasts
    Route::resource('podcasts', PodcastController::class);
    Route::resource('podcasts.episodes', EpisodeController::class)->shallow();
    Route::get('episode/plays', [PlaysController::class, 'index'])->name('episodes.plays.index');
    Route::resource('episodes.plays', PlaysController::class)->shallow()->except(['index', 'show']);
    Route::post('episodes/{episode}/rating', [EpisodeRatingController::class, 'update'])->name('episodes.rating.update');
    Route::delete('episodes/{episode}/rating', [EpisodeRatingController::class, 'destroy'])->name('episodes.rating.destroy');

    // Accounts
    Route::resource('accounts', AccountController::class);

    Route::resource('tags', TagController::class);

    Route::resource('notes', NoteController::class);
});
