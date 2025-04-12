<?php

namespace App\Http\Controllers\Podcasts;

use App\Http\Controllers\Controller;
use App\Models\Podcasts\Episode;
use App\Models\Podcasts\EpisodeRating;
use Illuminate\Http\Request;

class EpisodeRatingController extends Controller
{
    public function update(Request $request, Episode $episode)
    {
        $valid = $request->validate([
            'rating' => 'integer|min:1|max:5',
            'notes' => 'nullable',
        ]);
        $rating = EpisodeRating::whereBelongsTo($request->user())
            ->whereBelongsTo($episode)
            ->first();
        if (! $rating) {
            $rating = new EpisodeRating();
            $rating->episode_id = $episode->id;
            $rating->user_id = $request->user()->id;
        }
        $rating->rating = $valid['rating'];
        $rating->notes = isset($valid['notes']) ? $valid['notes'] : null;
        $rating->save();

        return redirect(route('episodes.show', $episode));
    }

    public function destroy(Request $request, EpisodeRating $rating)
    {
        $episode = $rating->episode;
        $rating->delete();

        return redirect(route('episodes.show', $episode));
    }
}
