<x-mail::message>
# Welcome to {{ config('app.name') }}!

Thanks for signing up for {{ config('app.name') }}! Head on over to <a href="{{ route('accounts') }}">integrations</a> to setup Strava or Listenbrainz. Or get started by creating your first location and checkin!

<x-mail::button :url="url('/')">
Let's Go!
</x-mail::button>

Head over to <a href="https://whenandwhat.me">whenandwhat.me</a> for tips on adding the app to your home screen or configuring your phone to automatically check-in to a location.

<x-mail::button url="https://whenandwhat.me/faq">
View Tips
</x-mail::button>

Your free trial expires on {{ $user->trail_ends_at->toDateString() }}. Please <a href="https://whenandwhat.me/contact">let us know</a> if you run into any issue or have any questions!
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
