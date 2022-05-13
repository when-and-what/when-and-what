<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Checkins\CreatePendingCheckinRequest;
use App\Models\Locations\PendingCheckin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PendingCheckinController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PendingCheckin::class, 'pending');
    }

    /**
     * Display a listing of the resource.
     *! this is not restricted by the Pending:class policy
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return PendingCheckin::whereBelongsTo($request->user())
            ->orderBy('checkin_at', 'DESC')
            ->get();
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
    public function show(PendingCheckin $pending)
    {
        return $pending;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locations\PendingCheckin  $pendingCheckin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PendingCheckin $pending)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locations\PendingCheckin  $pendingCheckin
     * @return \Illuminate\Http\Response
     */
    public function destroy(PendingCheckin $pending)
    {
        //
    }
}
