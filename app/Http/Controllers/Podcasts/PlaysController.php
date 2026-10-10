<?php

namespace App\Http\Controllers\Podcasts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Podcasts\UpdateEpisodePlayRequest;
use App\Models\Podcasts\EpisodePlay;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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

    public function edit(EpisodePlay $play): View
    {
        $this->authorize('update', $play);

        return view('podcasts.episodes.edit-play', [
            'play' => $play->load('episode.podcast'),
        ]);
    }

    public function update(UpdateEpisodePlayRequest $request, EpisodePlay $play): RedirectResponse
    {
        $play->played_at = Carbon::parse($request->validated('played_at'), $request->user()->timezone)
            ->tz(config('app.timezone'));
        $play->save();

        return redirect(route('podcasts.plays.edit', $play));
    }

    public function destroy(EpisodePlay $play): RedirectResponse
    {
        $this->authorize('delete', $play);

        $play->delete();

        return redirect(route('podcasts.plays'));
    }
}
