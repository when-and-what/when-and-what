<?php

namespace App\Http\Controllers\Podcasts;

use App\Http\Controllers\Controller;
use App\Models\Podcasts\Episode;
use App\Models\Podcasts\EpisodePlay;
use App\Models\Podcasts\EpisodeRating;
use App\Models\Podcasts\Podcast;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Podcast $podcast)
    {
        return view('podcasts.episodes.all', [
            'episodes' => Episode::whereBelongsTo($podcast)
                ->orderBy($podcast->feed ? 'published_at' : 'created_at', 'DESC')
                ->with('plays', function ($query) use ($request) {
                    $query->whereBelongsTo($request->user());
                })
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
    public function show(Request $request, Episode $episode)
    {
        return view('podcasts.episodes.show', [
            'episode' => $episode,
            'rating' => EpisodeRating::whereBelongsTo($request->user())
                ->whereBelongsTo($episode)
                ->first(),
            'plays' => EpisodePlay::whereBelongsTo($request->user())
                ->whereBelongsTo($episode)
                ->get(),
        ]);
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
