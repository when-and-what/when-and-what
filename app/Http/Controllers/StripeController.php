<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function success(Request $request)
    {
        return view('subscription', [
            'stripe' => 'success',
            'user' => $request->user(),
        ]);
    }

    public function cancel(Request $request)
    {
        return view('subscription', [
            'stripe' => 'cancel',
            'user' => $request->user(),
        ]);
    }
}
