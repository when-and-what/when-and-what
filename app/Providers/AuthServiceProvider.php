<?php

namespace App\Providers;

use App\Models\Locations\Category;
use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\Locations\PendingCheckin;
use App\Policies\Locations\CategoryPolicy;
use App\Policies\Locations\CheckinPolicy;
use App\Policies\Locations\LocationPolicy;
use App\Policies\PendingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Checkin::class => CheckinPolicy::class,
        Location::class => LocationPolicy::class,
        PendingCheckin::class => PendingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
