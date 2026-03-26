<?php

namespace App\Listeners;

use App\Mail\TrialMail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NewUserTrial
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $user->trial_ends_at = now()->addDays(31);
        $user->save();

        Mail::send(new TrialMail($user));
    }
}
