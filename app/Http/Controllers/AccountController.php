<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountUser;
use App\Services\UserAccount;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('accounts.list', [
            'accounts' => Account::with([
                'users' => function ($query) use ($request) {
                    return $query->where('user_id', $request->user()->id);
                },
            ])->get(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        if($account->slug === 'google') {
            return Socialite::driver($account->slug)
            ->scopes(explode(',', $account->scope))
            ->with(["access_type" => "offline", "prompt" => "consent select_account"])
            ->redirect();
        }
        else {
            return Socialite::driver($account->slug)
            ->scopes(explode(',', $account->scope))
            ->redirect();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Account $account)
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Account $account)
    {
        AccountUser::whereBelongsTo($account)->whereBelongsTo($request->user())->delete();
        return redirect(route('accounts.index'));
    }
}
