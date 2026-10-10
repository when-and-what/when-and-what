<?php

use App\Models\Podcasts\EpisodePlay;
use App\Models\User;

test('owner can view the edit play page', function () {
    $user = User::factory()->create(['timezone' => 'America/Denver']);
    $play = EpisodePlay::factory()->create([
        'user_id' => $user->id,
        'played_at' => '2023-02-02 02:00:00',
    ]);

    $this->actingAs($user)->get(route('podcasts.plays.edit', $play))
        ->assertOk()
        ->assertSee('2023-02-01T19:00')
        ->assertSee(route('podcasts.plays.destroy', $play));
});

test('update converts played at from user timezone and only changes played at', function () {
    $user = User::factory()->create(['timezone' => 'America/Denver']);
    $play = EpisodePlay::factory()->create([
        'user_id' => $user->id,
        'played_at' => '2023-02-02 02:00:00',
        'seconds' => 600,
    ]);
    $episodeId = $play->episode_id;

    $this->actingAs($user)->put(route('podcasts.plays.update', $play), [
        'played_at' => '2023-02-01T09:20',
        'seconds' => 5,
        'episode_id' => 'other',
        'user_id' => 999,
    ])->assertRedirect(route('podcasts.plays.edit', $play))
        ->assertSessionHasNoErrors();

    $play->refresh();
    expect($play->played_at->toDateTimeString())->toBe('2023-02-01 16:20:00')
        ->and($play->seconds)->toBe(600)
        ->and($play->episode_id)->toBe($episodeId)
        ->and($play->user_id)->toBe($user->id);
});

test('update requires a valid played at', function () {
    $user = User::factory()->create();
    $play = EpisodePlay::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->put(route('podcasts.plays.update', $play), [
        'played_at' => 'not a date',
    ])->assertSessionHasErrors('played_at');
});

test('owner can delete a play', function () {
    $user = User::factory()->create();
    $play = EpisodePlay::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->delete(route('podcasts.plays.destroy', $play))
        ->assertRedirect(route('podcasts.plays'));

    $this->assertSoftDeleted($play);
});

test('other users cannot edit, update, or delete a play', function () {
    $user = User::factory()->create();
    $play = EpisodePlay::factory()->create(['played_at' => '2023-02-02 02:00:00']);

    $this->actingAs($user)->get(route('podcasts.plays.edit', $play))->assertForbidden();
    $this->actingAs($user)->put(route('podcasts.plays.update', $play), [
        'played_at' => '2023-02-01T09:20',
    ])->assertForbidden();
    $this->actingAs($user)->delete(route('podcasts.plays.destroy', $play))->assertForbidden();

    $this->assertNotSoftDeleted($play);
    expect($play->fresh()->played_at->toDateTimeString())->toBe('2023-02-02 02:00:00');
});
