<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        return view('accounts.list', [
            'accounts' => Account::with([
                'users' => function ($query) use ($request) {
                    return $query->where('user_id', $request->user()->id);
                },
            ])->get(),
        ]);
    }

    public function edit(Account $account): View
    {
        return view('accounts.account', [
            'account' => $account,
        ]);
    }

    public function update(Request $request, Account $account): RedirectResponse
    {
        $validated = $request->validate([
            'username' => $account->edit_username ? 'required' : 'nullable',
            'token' => $account->edit_token ? 'required' : 'nullable',
        ]);

        $au = new AccountUser();
        $au->user_id = $request->user()->id;
        $au->account_id = $account->id;
        $au->account_user_id = null;
        $au->refresh_token = '';
        $au->username = $validated['username'] ?? '';
        $au->token = $validated['token'] ?? '';
        $au->save();

        return redirect(route('accounts.index'));
    }

    /**
     * Remove the account for the authenticated user.
     */
    public function destroy(Request $request, Account $account): RedirectResponse
    {
        AccountUser::whereBelongsTo($account)->whereBelongsTo($request->user())->delete();

        return redirect(route('accounts.index'));
    }
}
