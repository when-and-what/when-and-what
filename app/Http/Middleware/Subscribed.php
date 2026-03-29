<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->subscribed('default') && ! $request->user()->onTrial()) {
            // Redirect user to billing page and ask them to subscribe...
            return redirect('/subscription');
        }

        return $next($request);
    }
}
