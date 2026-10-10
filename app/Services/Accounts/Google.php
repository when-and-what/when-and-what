<?php

namespace App\Services\Accounts;

use App\Services\UserAccount;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

class Google extends UserAccount
{
    public function socialite(): Provider
    {
        return Socialite::driver('google');
    }
}
