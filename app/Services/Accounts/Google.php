<?php

namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Models\User;
use App\Services\UserAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class Google extends UserAccount
{
    public function socialite(): \Laravel\Socialite\Contracts\Provider
    {
        return Socialite::driver('google');
    }
}
