<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $upgrade = false;
        if ($request->user()->subscribed()) {
            if ($request->user()->subscribedToPrice(config('services.stripe.plans.web.annual'), 'default')) {
                $upgrade = 'annual-mobile';
            } elseif ($request->user()->subscribedToPrice(config('services.stripe.plans.web.biannual'), 'default')) {
                $upgrade = 'bi-annual-mobile';
            } elseif ($request->user()->subscribedToPrice(config('services.stripe.plans.web.monthly'), 'default')) {
                $upgrade = 'monthly-mobile';
            }
        }

        return view('subscription', [
            'upgrade' => $upgrade,
            'user' => $request->user(),
        ]);
    }

    public function create(Request $request, string $plan)
    {
        $price = match ($plan) {
            'annual' => config('services.stripe.plans.web.annual'),
            'bi-annual' => config('services.stripe.plans.web.biannual'),
            'monthly' => config('services.stripe.plans.web.monthly'),

            'annual-mobile' => config('services.stripe.plans.mobile.annual'),
            'bi-annual-mobile' => config('services.stripe.plans.mobile.biannual'),
            'monthly-mobile' => config('services.stripe.plans.mobile.monthly'),
        };

        if (! $price) {
            abort(404);
        }

        return $request->user()
            ->newSubscription('default', $price)
            ->allowPromotionCodes()
            ->checkout([
                'success_url' => route('subscription.success'),
                'cancel_url' => route('subscription.cancel'),
            ]);
    }

    public function edit(Request $request): RedirectResponse
    {
        return $request->user()->redirectToBillingPortal(route('dashboard'));
    }

    public function update(SubscriptionUpdateRequest $request)
    {
        $price = match ($request->plan) {
            'annual-mobile' => config('services.stripe.plans.mobile.annual'),
            'bi-annual-mobile' => config('services.stripe.plans.mobile.biannual'),
            'monthly-mobile' => config('services.stripe.plans.mobile.monthly'),
        };
        $request->user()->subscription('default')->swapAndInvoice($price);

        return redirect(route('subscription'));
    }
}
