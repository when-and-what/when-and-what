<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;

class MapLocationsController extends Controller
{
    public function __invoke()
    {
        return view('locations.map');
    }
}
