<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('services.fitbit.client_id') && ! Account::where('slug', 'fitbit')->first()) {
            Account::create([
                'name' => 'Fitbit',
                'slug' => 'fitbit',
                'service' => 'App\Services\Accounts\Fitbit',
                'scope' => 'activity, heartrate, location, nutrition, profile, sleep, weight',
            ]);
        }
        if (config('services.trakt.client_id') && ! Account::where('slug', 'trakt')->first()) {
            Account::create([
                'name' => 'Trakt',
                'slug' => 'trakt',
                'service' => 'App\Services\Accounts\Trakt',
                'scope' => '',
            ]);
        }
        if (config('services.todoist.client_id') && ! Account::where('slug', 'todoist')->first()) {
            Account::create([
                'name' => 'ToDoist',
                'slug' => 'todoist',
                'service' => 'App\Services\Accounts\Todoist',
                'scope' => 'data:read',
            ]);
        }
        if (config('services.todoist.client_id') && ! Account::where('slug', 'todoist')->first()) {
            Account::create([
                'name' => 'Google',
                'slug' => 'google',
                'service' => 'App\Services\Accounts\Google',
                'scope' => 'photoslibrary.readonly.appcreateddata',
            ]);
        }
        if (! Account::where('slug', 'listenbrainz')->first()) {
            Account::create([
                'name' => 'Listenbrainz',
                'slug' => 'listenbrainz',
                'service' => 'App\Services\Accounts\Listenbrainz',
                'scope' => '',
                'edit_username' => true,
            ]);
        }
        if (! Account::where('slug', 'pocketcasts')->first()) {
            Account::create([
                'name' => 'Pocket Casts',
                'slug' => 'pocketcasts',
                'service' => 'App\Services\Accounts\Pocketcasts',
                'scope' => '',
                'edit_token' => true,
            ]);
        }
    }
}
