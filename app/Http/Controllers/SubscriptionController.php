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
            'annual' => 'price_1TBP9E5kmQIHjkl8w5FL4fwV',
            'bi-annual' => 'price_1TBP945kmQIHjkl8h0vJjMO0',
            default => 'price_1TBP8f5kmQIHjkl8QGlSRCW2',
        };

        return $request->user()
            ->newSubscription('prod_U9iZIPCuYVLR9f', $price)
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
