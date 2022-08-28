<?php

namespace App\Http\Controllers\Podcasts;

use App\Http\Controllers\Controller;
use App\Models\Podcasts\Episode;
use App\Models\Podcasts\EpisodeRating;
use Illuminate\Http\Request;

class EpisodeRatingController extends Controller
{
    public function update(Request $request, ?Episode $episode)
    {
    }

    public function destroy(Request $request, EpisodeRating $rating)
    {
    }
}
