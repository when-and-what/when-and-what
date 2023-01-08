<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DayController extends Controller
{
    public function index(Request $request)
    {
        return $this->_displayDay($request->user(), now($request->user()->timezone));
    }

    public function day(Request $request, int $year, int $month, int $day)
    {
        $date = Carbon::create($year, $month, $day, 0, 0, 0, $request->user()->timezone);

        return $this->_displayDay($request->user(), $date);
    }

    private function _displayDay(User $user, Carbon $today)
    {
        return view('dashboard', [
            'today' => $today,
            'tomorrow' => $today->copy()->addDay(),
            'yesterday' => $today->copy()->subDay(),
            'user' => $user,
        ]);
    }
}
