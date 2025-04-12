<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\LocationSearchRequest;
use App\Models\Locations\Location;

class LocationSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LocationSearchRequest $request)
    {
        return view('locations.list', [
            'locations' => Location::whereBelongsTo($request->user())
                ->whereLike('name', '%'.$request->input('search').'%')
                ->with('category')
                ->orderBy('updated_at', 'DESC')
                ->paginate(20),
        ]);
    }
}
