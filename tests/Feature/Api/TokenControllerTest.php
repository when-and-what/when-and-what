<?php

use App\Models\User;

test('create personal access token', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/token/create', [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'test iPhone',
    ])
        ->assertStatus(200)
        ->assertJsonStructure(['token']);
    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'name' => 'test iPhone',
    ]);
});

test('rate limited to 5', function () {
    $user = User::factory()->create();
    for($i = 0; $i < 5; $i++) {
        $this->post('/api/token/create', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'test '.$i,
        ])
        ->assertStatus(200)
        ->assertJsonStructure(['token']);
    }
    $this->post('/api/token/create', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'test '.$i,
        ])
        ->assertStatus(429);
    $this->postJson('/api/token/create', [
            'email' => 'something@else',
            'password' => 'password',
            'device_name' => 'test '.$i,
        ])
        ->assertStatus(422);
});
