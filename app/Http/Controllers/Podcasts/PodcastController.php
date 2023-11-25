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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('podcasts.podcast', [
            'podcast' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PodcastRequest $request)
    {
        $podcast = new Podcast();
        $podcast->fill($request->safe()->all());
        if ($request->nickname == null) {
            $podcast->nickname = $podcast->name;
        }
        $podcast->created_by = $request->user()->id;
        $podcast->save();
        return redirect('podcasts');
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Podcast $podcast)
    {
        return view('podcasts.podcast', [
            'podcast' => $podcast,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PodcastRequest $request, Podcast $podcast)
    {
        $podcast->fill($request->safe()->all());
        $podcast->save();
        return redirect('podcasts');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Podcast $podcast)
    {
        if ($podcast->created_by != Auth()->user()->id) {
            abort(401, 'You do not have access to delete this podcast');
        }
        $podcast->delete();
        return redirect('podcasts');
    }
}
