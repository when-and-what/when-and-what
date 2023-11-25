<?php

namespace App\Http\Controllers\Podcasts;

use App\Http\Controllers\Controller;
use App\Models\Podcasts\Episode;
use App\Models\Podcasts\EpisodePlay;
use App\Models\Podcasts\PodcastPlay;
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
                    DB::raw('max(played_at) as last_played_at')
                )
                ->orderBy('last_played_at', 'desc')
                ->withCasts(['last_played_at' => 'datetime'])
                ->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Episode $episode)
    {
        return view('podcasts.play', [
            'episode' => $episode,
            'play' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EpisodePlay $play)
    {
        return view('podcasts.play', [
            'episode' => $play->episode,
            'play' => $play,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EpisodePlay $play)
    {
        $validated = $request->validate([
            'seconds' => 'required|numeric|integer',
        ]);
        $play->seconds = $validated['seconds'];
        $play->save();
        return redirect(route('episodes.show', $play->episode));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EpisodePlay $play)
    {
        $episode = $play->episode;
        $play->delete();
        return redirect(route('episodes.show', $episode));
    }
}
