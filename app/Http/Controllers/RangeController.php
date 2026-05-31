<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class RangeController extends Controller
{
    public function __invoke(Request $request, string $start, string $end)
    {
        $user = $request->user();
        $startDate = Carbon::createFromFormat('Y-m-d', $start, $user->timezone)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $end, $user->timezone)->startOfDay();

        return view('range', [
            'start' => $startDate,
            'end' => $endDate,
        ]);
    }
}
