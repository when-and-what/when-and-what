<?php

use App\Jobs\PodcastLookup;
use App\Models\Podcasts\Podcast;
use Illuminate\Support\Facades\Http;

beforeEach(function() {
    $podcast = new Podcast;
    $podcast->id = 'cb2108e0-8619-013a-d7f7-0acc26574db2';
    $podcast->title = 'Search Engine';
    $podcast->save();
});

test('update podcast details from api', function() {
    $this->assertTrue(file_exists('tests/Feature/Podcasts/podcast.json'));
    Http::fake([
        'https://podcast-api.pocketcasts.com/podcast/full/cb2108e0-8619-013a-d7f7-0acc26574db2' => Http::response(file_get_contents('tests/Feature/Podcasts/podcast.json')),
        'https://static.pocketcasts.com/discover/images/webp/480/cb2108e0-8619-013a-d7f7-0acc26574db2.webp' => Http::response(file_get_contents('tests/Feature/Podcasts/podcast.webp')),
        '*' => Http::response('', 409),
    ]);

    $job = new PodcastLookup('cb2108e0-8619-013a-d7f7-0acc26574db2');
    $job->handle();

    $this->assertDatabaseHas('podcasts', [
        'id' => 'cb2108e0-8619-013a-d7f7-0acc26574db2',
        'description' => "We try to make sense of the world, one question at a time. No question too big, no question too small. Hosted by PJ Vogt, edited by Sruthi Pinnamaneni.\n***Named one of the best podcasts by Vulture, Time, The Economist, & Vogue. (OK, in 2023, but still...)***",
        'author' => "PJ Vogt",
        'url' => "http://www.pjvogt.com",
        'is_private' => 0,
        'image' => "podcasts/cb2108e0-8619-013a-d7f7-0acc26574db2.webp",
    ]);
});

//https://podcast-api.pocketcasts.com/podcast/full/cb2108e0-8619-013a-d7f7-0acc26574db2
