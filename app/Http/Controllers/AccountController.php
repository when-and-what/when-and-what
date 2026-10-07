<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        AccountUser::updateOrCreate(
            ['user_id' => $request->user()->id, 'account_id' => $account->id],
            [
                'account_user_id' => null,
                'refresh_token' => '',
                'username' => $validated['username'] ?? '',
                'token' => $validated['token'] ?? '',
                'auth_failed_at' => null,
            ],
        );

        return redirect(route('accounts.index'));
    }

    /**
     * Remove the account for the authenticated user.
     */
    public function destroy(Request $request, Account $account): RedirectResponse
    {
        AccountUser::whereBelongsTo($account)->whereBelongsTo($request->user())->delete();
        Cache::forget($request->user()->id.'-strava-athlete');

        return redirect(route('accounts.index'));
    }
}
