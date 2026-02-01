<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirects to oAuth destination.
     */
    public function redirect(Account $account): RedirectResponse
    {
        return Socialite::driver($account->slug)
            ->scopes(explode(',', $account->scope))
            ->redirect();
    }

    /**
     * Saves the response from oAuth service.
     */
    public function update(Request $request, Account $account): RedirectResponse
    {
        $user = Socialite::driver($account->slug)->user();

        $au = new AccountUser();
        $au->user_id = $request->user()->id;
        $au->account_id = $account->id;
        $au->account_user_id = $user->id;
        $au->token = $user->token;
        $au->refresh_token = $user->refreshToken;
        $au->save();

        return redirect(route('accounts.index'));
    }
}
