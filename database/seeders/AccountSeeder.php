<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        if (config('services.fitbit.client_id') && !Account::where('slug', 'fitbit')->first()) {
            Account::create([
                'name' => 'Fitbit',
                'slug' => 'fitbit',
                'service' => 'App\Services\Accounts\Fitbit',
                'scope' => 'activity, heartrate, location, nutrition, profile, sleep, weight',
            ]);
        }
        if (config('services.trakt.client_id') && !Account::where('slug', 'trakt')->first()) {
            Account::create([
                'name' => 'Trakt',
                'slug' => 'trakt',
                'service' => 'App\Services\Accounts\Trakt',
                'scope' => '',
            ]);
        }
    }
}
