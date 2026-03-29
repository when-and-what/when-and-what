<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        return view('subscription', [
            'user' => $request->user(),
        ]);
    }

    public function create(Request $request, string $plan)
    {
        $price = match ($plan) {
            'annual' => config('services.stripe.plans.annual'),
            'bi-annual' => config('services.stripe.plans.biannual'),
            default => config('services.stripe.plans.monthly'),
        };

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
}
