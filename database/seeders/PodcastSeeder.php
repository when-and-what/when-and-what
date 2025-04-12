<?php

namespace Database\Seeders;

use App\Models\Podcasts\Episode;
use App\Models\Podcasts\EpisodePlay;
use App\Models\Podcasts\EpisodeRating;
use App\Models\Podcasts\Podcast;
use App\Models\User;
use Illuminate\Database\Seeder;

class PodcastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'natec23@gmail.com')->first();
        $podcasts = Podcast::factory()
            ->count(3)
            ->create(['created_by' => $user->id]);
        foreach ($podcasts as $podcast) {
            $episodes = Episode::factory()
                ->count(random_int(1, 9))
                ->create([
                    'created_by' => $podcast->created_by,
                    'podcast_id' => $podcast->id,
                ]);
            foreach ($episodes as $episode) {
                if (random_int(0, 1) == 1) {
                    EpisodeRating::factory()->create([
                        'user_id' => $episode->created_by,
                        'episode_id' => $episode->id,
                    ]);
                }
                EpisodePlay::factory()
                    ->count(random_int(1, 9))
                    ->create([
                        'user_id' => $episode->created_by,
                        'episode_id' => $episode->id,
                    ]);
            }
        }
    }
}
