<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckinRequest;
use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\Locations\PendingCheckin;
use Carbon\Carbon;

class CheckinController extends Controller
{
    public function __invoke(CheckinRequest $request)
    {
        if($request->location){
            $location = Location::findOrFail($request->location);
            // TODO: check policy
            if($location->user_id != $request->user()->id) {
                return response('', 403);
            }
            $checkin = new Checkin();
            $checkin->location_id = $request->location;
        }
        else {
            $checkin = new PendingCheckin();
            $checkin->latitude = $request->latitude;
            $checkin->longitude = $request->longitude;
            $checkin->name = $request->name;
        }
        $checkin->user_id = $request->user()->id;
        $checkin->note = $request->note;
        if ($request->date) {
            $checkin->checkin_at = new Carbon($request->date, 'UTC');
        } else {
            $checkin->checkin_at = new Carbon();
        }
        $checkin->save();

        return response([
            'checkin' => $checkin,
            'type' => ($checkin instanceof PendingCheckin ? 'pending' : 'checkin'),
        ], 201);
    }
}
