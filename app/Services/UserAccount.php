<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountUser;
use App\Models\User;

class UserAccount
{
    protected $account;
    protected $accountUser;

    public function __construct(Account $account, User $user)
    {
        $this->account = $account;
        $this->accountUser = AccountUser::whereBelongsTo($account)
            ->whereBelongsTo($user)
            ->first();
    }
}
