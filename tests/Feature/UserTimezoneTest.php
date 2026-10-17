<?php

use App\Models\User;

test('user can update timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);

    $response = $this->actingAs($user)->patch(route('user.timezone.update'), [
        'timezone' => 'America/Denver',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
    expect($user->fresh()->timezone)->toBe('America/Denver');
});

test('update timezone rejects invalid timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);

    $response = $this->actingAs($user)->patch(route('user.timezone.update'), [
        'timezone' => 'Not/AZone',
    ]);

    $response->assertSessionHasErrors('timezone');
    expect($user->fresh()->timezone)->toBe('America/Chicago');
});

test('update timezone requires timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);

    $this->actingAs($user)
        ->patch(route('user.timezone.update'), [])
        ->assertSessionHasErrors('timezone');
});

test('guest cannot update timezone', function () {
    $this->patch(route('user.timezone.update'), ['timezone' => 'America/Denver'])
        ->assertRedirect(route('login'));
});

test('layout includes timezone prompt for authenticated user', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);

    $this->actingAs($user)
        ->get(route('checkins.index'))
        ->assertOk()
        ->assertSee('id="tz-prompt"', false)
        ->assertSee('data-profile-tz="America/Chicago"', false);
});
