<?php

namespace App\Providers;

use App\Models\Locations\PendingCheckin;
use App\Policies\Locations\PendingCheckinPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Strava\StravaExtendSocialite;
use SocialiteProviders\Trakt\TraktExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::policy(PendingCheckin::class, PendingCheckinPolicy::class);

        $this->bootRoute();
        $this->bootSocialite();
    }

    public function bootSocialite(): void
    {
        Event::listen(SocialiteWasCalled::class, TraktExtendSocialite::class.'@handle');
        Event::listen(SocialiteWasCalled::class, StravaExtendSocialite::class.'@handle');
    }

    public function bootRoute(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email);
        });
    }
}
