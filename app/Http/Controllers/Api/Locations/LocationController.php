<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Models\Locations\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *!this is not restricted  by the Location policy.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locations = Location::whereBelongsTo($request->user());
        if (
            is_numeric($request->input('north')) &&
            is_numeric($request->input('south')) &&
            is_numeric($request->input('east')) &&
            is_numeric($request->input('west'))
        ) {
            $locations = $locations->map(
                $request->input('north'),
                $request->input('south'),
                $request->input('east'),
                $request->input('west')
            );
        }

        return $locations->paginate(30);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        //
    }
}
