<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\LocationSearchRequest;
use App\Models\Locations\Location;
use Illuminate\View\View;

class LocationSearchController extends Controller
{
    public function __invoke(LocationSearchRequest $request): View
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
