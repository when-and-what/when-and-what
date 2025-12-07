<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Locations\CheckinController;
use App\Http\Controllers\Api\Locations\LocationController;
use App\Http\Controllers\Api\Locations\PendingCheckinController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\Podcasts\EpisodePlayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('dashboard/checkins/{date}', [DashboardController::class, 'checkins']);
    Route::get('dashboard/pending_checkins/{date}', [DashboardController::class, 'pendingCheckins']);
    Route::get('dashboard/notes/{date}', [DashboardController::class, 'notes']);
    Route::get('dashboard/podcasts/{date}', [DashboardController::class, 'podcasts']);
    Route::get('/dashboard/{account}/{date}', [DashboardController::class, 'day']);
    Route::post('podcasts/play', EpisodePlayController::class);
    Route::apiResource('locations/checkins/pending', PendingCheckinController::class);
    Route::apiResource('locations/checkins', CheckinController::class);
    Route::apiResource('locations', LocationController::class);

    Route::get('accounts/user', [AccountController::class, 'userAccounts']);

    Route::post('notes/dashboard', [NoteController::class, 'storeDashboard'])->name('notes.dashboard');
    Route::resource('notes', NoteController::class);
});
