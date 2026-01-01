<?php

namespace App\Http\Controllers;

use App\Models\Trackers\Tracker;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        $tomorrow = $today->copy()->addDay();
        $yesterday = $today->copy()->subDay();

        $trackers = Tracker::whereBelongsTo($user)
            ->with('events', function (HasMany $query) use ($tomorrow, $yesterday) {
                $query->before($tomorrow)->after($yesterday);
            })
            ->whereHas('events', function (Builder $query) use ($tomorrow, $yesterday) {
                $query->before($tomorrow)->after($yesterday);
            })
            ->get();

        return view('dashboard', [
            'trackers' => $trackers,
            'today' => $today,
            'tomorrow' => $tomorrow,
            'yesterday' => $yesterday,
            'user' => $user,
        ]);
    }
}
