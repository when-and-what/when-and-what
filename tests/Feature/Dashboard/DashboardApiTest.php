<?php

use App\Models\Account;
use App\Models\AccountUser;
use App\Models\Locations\Category;
use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\Locations\PendingCheckin;
use App\Models\User;
use App\Services\Accounts\Trakt;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;

test('it returns checkins for dashboard', function () {
    $date = '2023-02-01';
    $user = User::factory()->create(['timezone' => 'America/Denver']);
    $checkin = Checkin::factory()->create([
        'user_id' => $user->id,
        'checkin_at' => $date.' 12:00:00',
    ]);
    Checkin::factory(5)->create(['checkin_at' => $date.' 12:00:00']);

    $response = $this->actingAs($user)->getJson('api/dashboard/checkins/'.$date);
    $response
        ->assertStatus(200)
        ->assertJsonCount(1, 'events')
        ->assertJsonCount(1, 'pins')
        ->assertJsonFragment(['subTitle' => $checkin->note]);
});

test('icon returned for checkin event', function () {
    $user = User::factory()->create(['timezone' => 'America/Denver']);
    $categoryNoEmoji = Category::factory()->create(['emoji' => null]);
    $category1 = Category::factory()->create();
    $category2 = Category::factory()->create();
    $this->assertNotEquals($category1->emoji, $category2->emoji);

    // No location category
    $checkin = Checkin::factory()->create([
        'user_id' => $user->id,
        'checkin_at' => '2023-01-01 12:00:00',
    ]);
    $response = $this->actingAs($user)->getJson('api/dashboard/checkins/2023-01-01');
    $response
        ->assertStatus(200)
        ->assertJsonCount(1, 'events')
        ->assertJsonCount(1, 'pins')
        ->assertJsonFragment(['icon' => '📍']);

    // Location category but no emoji
    $location = Location::factory()->create();
    $location->category()->save($categoryNoEmoji);
    $checkin = Checkin::factory()->create([
        'user_id' => $user->id,
        'location_id' => $location->id,
        'checkin_at' => '2023-01-02 12:00:00',
    ]);
    $response = $this->actingAs($user)->getJson('api/dashboard/checkins/2023-01-02');
    $response
        ->assertStatus(200)
        ->assertJsonCount(1, 'events')
        ->assertJsonCount(1, 'pins')
        ->assertJsonFragment(['icon' => '📍']);

    // one category
    $location = Location::factory()->create();
    $location->category()->save($category1);
    $checkin = Checkin::factory()->create([
        'user_id' => $user->id,
        'location_id' => $location->id,
        'checkin_at' => '2023-01-03 12:00:00',
    ]);
    $response = $this->actingAs($user)->getJson('api/dashboard/checkins/2023-01-03');
    $response
        ->assertStatus(200)
        ->assertJsonCount(1, 'events')
        ->assertJsonCount(1, 'pins')
        ->assertJsonFragment(['icon' => $category1->emoji]);

    // two categories with icons
    $location = Location::factory()->create();
    $location->category()->save($category1);
    $location->category()->save($category2);
    $checkin = Checkin::factory()->create([
        'user_id' => $user->id,
        'location_id' => $location->id,
        'checkin_at' => '2023-01-04 12:00:00',
    ]);
    $response = $this->actingAs($user)->getJson('api/dashboard/checkins/2023-01-04');
    $response
        ->assertStatus(200)
        ->assertJsonCount(1, 'events')
        ->assertJsonCount(1, 'pins')
        ->assertJsonFragment(['icon' => $category1->emoji.$category2->emoji]);
});

test('it returns pending checkins for dashboard', function () {
    $date = '2023-02-01';
    $user = User::factory()->create(['timezone' => 'America/Denver']);
    $checkin = PendingCheckin::factory()->create([
        'user_id' => $user->id,
        'checkin_at' => $date.' 12:00:00',
    ]);
    PendingCheckin::factory(5)->create(['checkin_at' => $date.' 12:00:00']);

    $response = $this->actingAs($user)->getJson('api/dashboard/pending_checkins/'.$date);
    $response
        ->assertStatus(200)
        ->assertJsonCount(1, 'events')
        ->assertJsonCount(1, 'pins')
        ->assertJsonFragment(['subTitle' => $checkin->note]);
});

test('api user account', function () {
    $this->markTestIncomplete('what are we testing here?');

    $account = Account::where('slug', 'trakt')->first();
    $user = User::factory()->create();
    $au = new AccountUser;
    $au->user_id = $user->id;
    $au->account_id = $account->id;
    $au->account_user_id = $user->id;
    $au->token = '';
    $au->refresh_token = '';
    $au->save();

    $this->instance(
        Trakt::class,
        Mockery::mock(Trakt::class, function (MockInterface $mock) use ($user) {
            $mock->shouldReceive('__construct')->with(['user' => $user]);
        })
    );
    Http::fake(['*' => '']);
    $response = $this->actingAs($user)->getJson('api/dashboard/trakt/'.date('Y-m-d'));
});
