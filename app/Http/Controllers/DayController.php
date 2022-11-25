<?php

namespace App\Http\Controllers;

use App\Models\Account;
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
        $date = Carbon::create($year, $month, $day, 0, 0, 0, $request->user()->timezone);

        return $this->_displayDay($request->user(), $date);
    }

    private function _displayDay(User $user, Carbon $today)
    {
        $start = $today
            ->copy()
            ->startOfDay()
            ->setTimezone('UTC');
        $end = $today
            ->copy()
            ->endOfDay()
            ->setTimezone('UTC');

        return view('dashboard', [
            'accounts' => Account::UserAccount($user)->get(),
            'checkins' => Checkin::whereBelongsTo($user)
                ->after($start)
                ->before($end)
                ->with('location.category')
                ->orderBy('checkin_at', 'ASC')
                ->get(),
            'today' => $today,
            'tomorrow' => $today->copy()->addDay(),
            'yesterday' => $today->copy()->subDay(),
            'user' => $user,
        ]);
    }
}
