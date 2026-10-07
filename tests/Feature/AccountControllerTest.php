<?php

use App\Models\Account;
use App\Models\AccountUser;
use App\Models\User;

test('reconnecting an account updates the existing connection', function () {
    $this->actingAs($user = User::factory()->create());
    $account = Account::where('slug', 'pocketcasts')->first();

    $this->put(route('accounts.update', $account), ['token' => 'first'])->assertRedirect(route('accounts.index'));
    AccountUser::first()->update(['auth_failed_at' => now()]);

    $this->put(route('accounts.update', $account), ['token' => 'second'])->assertRedirect(route('accounts.index'));

    expect(AccountUser::where('user_id', $user->id)->where('account_id', $account->id)->count())->toBe(1);
    $userAccount = AccountUser::first();
    expect($userAccount->token)->toBe('second')
        ->and($userAccount->auth_failed_at)->toBeNull();
});
