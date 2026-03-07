<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function all()
    {
    }

    public function userAccounts(Request $request)
    {
        return Account::userAccount($request->user())->get();
    }
}
