<?php

namespace App\Http\Controllers;

use App\Models\Locations\Checkin;
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
        $date = now($request->user()->timezone);
        $date->setDate($year, $month, $day);

        return $this->_displayDay($request->user(), $date);
    }

    private function _displayDay(User $user, Carbon $today)
    {
        $start = now($today->timezone);
        $start->setDateTime($today->year, $today->month, $today->day, 0, 0, 0);
        $start->setTimezone('UTC');

        $end = now($today->timezone);
        $end->setDateTime($today->year, $today->month, $today->day, 23, 59, 59);
        $end->setTimezone('UTC');
        return view('dashboard', [
            'checkins' => Checkin::whereBelongsTo($user)
                ->after($start)
                ->before($end)
                ->with('location.category')
                ->orderBy('checkin_at', 'ASC')
                ->get(),
            'today' => $today,
            'tomorrow' => Carbon::make($today->copy()->tomorrow()),
            'yesterday' => Carbon::make($today->copy()->yesterday()),
        ]);
    }
}
