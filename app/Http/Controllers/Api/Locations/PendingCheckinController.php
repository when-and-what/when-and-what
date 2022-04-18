<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Checkins\CreatePendingCheckinRequest;
use App\Models\Locations\PendingCheckin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PendingCheckinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePendingCheckinRequest $request)
    {
        $checkin = new PendingCheckin();
        $checkin->latitude = $request->latitude;
        $checkin->longitude = $request->longitude;
        $checkin->name = $request->name;
        $checkin->user_id = $request->user()->id;
        $checkin->note = $request->note;
        if ($request->date) {
            $checkin->checkin_at = new Carbon($request->date, 'UTC');
        } else {
            $checkin->checkin_at = new Carbon();
        }
        $checkin->save();
        return response($checkin, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locations\PendingCheckin  $pendingCheckin
     * @return \Illuminate\Http\Response
     */
    public function show(PendingCheckin $pendingCheckin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locations\PendingCheckin  $pendingCheckin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PendingCheckin $pendingCheckin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locations\PendingCheckin  $pendingCheckin
     * @return \Illuminate\Http\Response
     */
    public function destroy(PendingCheckin $pendingCheckin)
    {
        //
    }
}
