<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapLocationsController extends Controller
{
    public function __invoke()
    {
        return view('locations.map');
    }
}
