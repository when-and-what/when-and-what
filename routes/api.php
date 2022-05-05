<?php

use App\Http\Controllers\Api\Locations\CheckinController;
use App\Http\Controllers\Api\Locations\LocationController;
use App\Http\Controllers\Api\Locations\PendingCheckinController;
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

Route::middleware('auth:sanctum')->group(function(){
    Route::post('podcasts/play', EpisodePlayController::class);
    Route::apiResource('locations/checkins/pending', PendingCheckinController::class);
    Route::apiResource('locations/checkins', CheckinController::class);
    Route::apiResource('locations', LocationController::class);
});
