<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MapLocationsController extends Controller
{
    public function __invoke(): View
    {
        return view('locations.map');
    }
}
