<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Models\Locations\PendingCheckin;
use Illuminate\Http\Request;

class PendingCheckinController extends Controller
{
    public function show(Request $request, PendingCheckin $checkin)
    {
        if ($request->expectsJson()) {
            return $checkin;
        }
        return view('locations.checkins.pending', [
            'checkin' => $checkin,
        ]);
    }
}
