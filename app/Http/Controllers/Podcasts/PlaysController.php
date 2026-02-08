<?php

namespace App\Http\Controllers\Podcasts;

use App\Http\Controllers\Controller;
use App\Models\Podcasts\EpisodePlay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaysController extends Controller
{
    public function index(Request $request)
    {
        return view('podcasts.episodes.recent', [
            'recent' => EpisodePlay::groupBy('episode_id')
                ->whereBelongsTo($request->user())
                ->select(
                    'episode_id',
                    DB::raw('sum(seconds) as seconds'),
                    DB::raw('max(play_date) as last_played_at')
                )
                ->orderBy('last_played_at', 'desc')
                ->withCasts(['last_played_at' => 'datetime'])
                ->paginate(20),
        ]);
    }
}
