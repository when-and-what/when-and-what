<?php

namespace App\Services\Accounts;

use App\Services\UserAccount;
use Laravel\Socialite\Facades\Socialite;

class Google extends UserAccount
{
    public function socialite(): \Laravel\Socialite\Contracts\Provider
    {
        return Socialite::driver('google');
    }
}
