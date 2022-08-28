<?php

namespace App\Http\Controllers\Podcasts;

use App\Http\Controllers\Controller;
use App\Models\Podcasts\Episode;
use App\Models\Podcasts\Podcast;
use App\Models\Podcasts\PodcastEpisodeRating;
use App\Models\Podcasts\PodcastPlay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Podcast $podcast)
    {
        return view('podcasts.episodes.all', [
            'episodes' => Episode::whereBelongsTo($podcast)
                ->orderBy($podcast->feed ? 'published_at' : 'created_at', 'DESC')
                ->paginate(20),
            'podcast' => $podcast,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Podcasts\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function show(Episode $episode)
    {
        return view('podcasts.episodes.show', [
            'episode' => $episode,
            'rating' => PodcastEpisodeRating::userEpisode(Auth::id(), $episode->id)->first(),
            'plays' => PodcastPlay::userEpisode(Auth::id(), $episode->id)->get(),
        ]);
    }

    public function rating(Request $request, Episode $episode)
    {
        $valid = $request->validate([
            'rating' => 'integer|min:1|max:5',
            'notes' => 'nullable',
        ]);
        $rating = PodcastEpisodeRating::firstOrNew([
            'episode_id' => $episode->id,
            'user_id' => Auth::id(),
        ]);
        $rating->rating = $valid['rating'];
        $rating->notes = $valid['notes'] ?? '';
        $rating->save();

        return redirect(route('episodes.show', $episode));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Podcasts\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function edit(Episode $episode)
    {
        return view('podcasts.episodes.edit', [
            'episode' => $episode,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Podcasts\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Episode $episode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Podcasts\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Episode $episode)
    {
        //
    }
}
