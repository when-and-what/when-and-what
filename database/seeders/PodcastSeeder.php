<?php

namespace Database\Seeders;

use App\Models\Podcasts\Episode;
use App\Models\Podcasts\EpisodePlay;
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
            ->create();
        foreach ($podcasts as $podcast) {
            $episodes = Episode::factory()
                ->count(random_int(1, 9))
                ->create([
                    'podcast_id' => $podcast->id,
                ]);
            foreach ($episodes as $episode) {
                EpisodePlay::factory()
                    ->count(random_int(1, 9))
                    ->create([
                        'user_id' => $user->id,
                        'episode_id' => $episode->id,
                    ]);
            }
        }
    }
}
