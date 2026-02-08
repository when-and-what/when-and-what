<?php

namespace App\Http\Controllers\Podcasts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Podcasts\PodcastRequest;
use App\Models\Podcasts\Episode;
use App\Models\Podcasts\Podcast;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('podcasts.list', [
            'podcasts' => Podcast::whereBelongsTo($request->user())->paginate(30),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Podcast $podcast)
    {
        return view('podcasts.show', [
            'episodes' => Episode::whereBelongsTo($podcast)
                ->orderBy($podcast->feed ? 'published_at' : 'created_at', 'DESC')
                ->paginate(20),
            'podcast' => $podcast,
        ]);
    }
}
