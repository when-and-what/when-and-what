<?php

use App\Models\User;
use Carbon\Carbon;

test('dashboard', function () {
    $user = User::factory()->create(['timezone' => 'America/New_York']);
    $tomorrow = now($user->timezone)->addDay()->startOfDay();
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertViewIs('dashboard')
        ->assertViewHas('tomorrow', $tomorrow);
});

test('redirected if not subscribed', function (?Carbon $date) {
    $user = User::factory()->create(['trial_ends_at' => $date]);
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('subscription'));
})->with([
    'no trial' => null,
    'expired' => now()->subDays(2),
]);

test('specific day', function () {
    $user = User::factory()->create(['timezone' => 'America/New_York']);

    $this->actingAs($user)
        ->get(route('day', [
            'year' => '2026',
            'month' => '2',
            'day' => '28',
        ]))
        ->assertViewIs('dashboard')
        ->assertViewHas('today', Carbon::create(2026, 2, 28, 0, 0, 0, 'America/New_York'));
});
