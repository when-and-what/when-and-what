<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserTimezoneRequest;
use Illuminate\Http\RedirectResponse;

class UserTimezoneController extends Controller
{
    public function __invoke(UpdateUserTimezoneRequest $request): RedirectResponse
    {
        $request->user()->update(['timezone' => $request->validated('timezone')]);

        return back();
    }
}
