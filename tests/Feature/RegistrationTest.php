<?php

use App\Providers\AppServiceProvider;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

test('registration screen can be rendered', function () {
    if (! Features::enabled(Features::registration())) {
        return $this->markTestSkipped('Registration support is not enabled.');
    }

    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('registration screen cannot be rendered if support is disabled', function () {
    if (Features::enabled(Features::registration())) {
        return $this->markTestSkipped('Registration support is enabled.');
    }

    $response = $this->get('/register');

    $response->assertStatus(404);
});

test('new users can register', function () {
    if (! Features::enabled(Features::registration())) {
        return $this->markTestSkipped('Registration support is not enabled.');
    }

    $this->freezeTime();
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@laravel.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ]);

    $response->assertRedirect(AppServiceProvider::HOME);
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@laravel.com',
        'trial_ends_at' => now()->plus(weeks: 2),
    ]);
});
