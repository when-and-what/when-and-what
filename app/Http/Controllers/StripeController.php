<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function success(Request $request)
    {
        return view('subscription.success');
    }

    public function cancel(Request $request)
    {
        return view('subscription', [
            'user' => $request->user(),
        ]);
    }
}
