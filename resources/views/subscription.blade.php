@extends('layouts.bootstrap')

@push('styles')
<style>
    .pricing-card {
        border: 1px solid var(--ww-border);
        border-radius: 1rem;
        padding: 2rem;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .pricing-card-featured {
        border-color: var(--ww-accent);
        box-shadow: 0 0 0 2px var(--ww-accent), 0 8px 24px rgba(13,148,136,.12);
    }
    .pricing-badge {
        position: absolute;
        top: -0.75rem;
        left: 50%;
        transform: translateX(-50%);
        background: var(--ww-accent);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.2rem 0.85rem;
        border-radius: 999px;
        white-space: nowrap;
    }
    .pricing-card-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .pricing-plan-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ww-muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.75rem;
    }
    .pricing-price {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .pricing-currency {
        font-size: 1.4rem;
        font-weight: 700;
        margin-top: 0.4rem;
        color: var(--ww-dark);
    }
    .pricing-amount {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--ww-dark);
        letter-spacing: -0.03em;
    }
    .pricing-period {
        font-size: 1rem;
        font-weight: 500;
        color: var(--ww-muted);
        align-self: flex-end;
        margin-bottom: 0.4rem;
    }
    .pricing-description {
        font-size: 0.85rem;
        color: var(--ww-muted);
        margin-bottom: 0;
    }
    .btn-cta {
        background: var(--ww-accent);
        border-color: var(--ww-accent);
        color: #fff;
        font-weight: 600;
    }
    .btn-outline-secondary:hover,.btn-cta:hover {
        background: var(--ww-accent-dark);
        border-color: var(--ww-accent-dark);
        color: #fff;
    }
    .subscription-active-icon {
        font-size: 2.5rem;
        color: var(--ww-accent);
        margin-bottom: 1rem;
    }
    .alert-success {
        background: #f0fdfa;
        border-color: #99f6e4;
        color: var(--ww-accent-dark);
        border-radius: 8px;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('full-content')

<div class="container py-5">

    @if(isset($stripe) && $stripe == 'success')
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-6 col-lg-5 text-center">
                <div class="alert alert-success" role="alert">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Thanks for signing up! <a href="https://whenandwhat.me/contact">Contact us</a> with any questions</span>
                </div>
            </div>
        </div>
    @elseif(isset($stripe) && $stripe == 'cancel')
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-6 col-lg-4">
                <div class="alert alert-warning" role="alert">
                    <span>No changes have been made to your account or subscription. <a href="https://whenandwhat.me/contact">Contact us</a> if you had any troubles or have any questions</span>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Back link + header ───────────────────────────────────────── --}}
    <div class="text-center mb-5">
        @if ($user->subscribed())
            <a href="{{ url()->previous() }}" class="page-back-link mb-3 d-inline-flex">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <h1 class="page-title mt-2">Your Subscription</h1>
            <p class="page-subtitle">Your account is active and all features are unlocked.</p>
        @else
            <h1 class="page-title mt-2">Choose a plan</h1>
            <p class="page-subtitle">Every <a href="https://whenandwhat.me/features" target="_blank">feature</a> included. No tiers, no feature gates — just choose how you want to pay.</p>
        @endif
    </div>

    @if ($user->subscribed())

        {{-- ── Active subscription ──────────────────────────────────── --}}
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-6 col-lg-4">
                <div class="pricing-card text-center">
                    <div class="pricing-card-header mb-0">
                        <div class="subscription-active-icon">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h2 class="pricing-plan-name">Active</h2>
                        <p class="pricing-description">You have full access to all features.</p>
                    </div>
                    <a href="{{ route('subscription.edit') }}" class="btn btn-outline-secondary w-100 btn-lg mt-auto">
                        Manage Subscription
                    </a>
                </div>
            </div>
        </div>

    @else

        {{-- ── Pricing cards ────────────────────────────────────────── --}}
        <div class="row justify-content-center g-4">

            {{-- Monthly --}}
            <div class="col-sm-10 col-md-4 col-lg-3">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h2 class="pricing-plan-name">Monthly</h2>
                        <div class="pricing-price">
                            <span class="pricing-currency">$</span><span class="pricing-amount">2</span><span class="pricing-period">/mo</span>
                        </div>
                        <p class="pricing-description">Pay as you go, cancel anytime.</p>
                    </div>
                    <a href="{{ route('subscription.create', 'monthly') }}" class="btn btn-outline-secondary w-100 btn-lg mt-auto">
                        Subscribe
                    </a>
                </div>
            </div>

            {{-- 6-Month --}}
            <div class="col-sm-10 col-md-4 col-lg-3">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h2 class="pricing-plan-name">6 Months</h2>
                        <div class="pricing-price">
                            <span class="pricing-currency">$</span><span class="pricing-amount">10</span><span class="pricing-period">/6 mo</span>
                        </div>
                        <p class="pricing-description">Save $2 vs monthly — renews every six months.</p>
                    </div>
                    <a href="{{ route('subscription.create', 'bi-annual') }}" class="btn btn-outline-secondary w-100 btn-lg mt-auto">
                        Subscribe
                    </a>
                </div>
            </div>

            {{-- Annual --}}
            <div class="col-sm-10 col-md-4 col-lg-3">
                <div class="pricing-card pricing-card-featured">
                    <div class="pricing-badge">Best value</div>
                    <div class="pricing-card-header">
                        <h2 class="pricing-plan-name">Annual</h2>
                        <div class="pricing-price">
                            <span class="pricing-currency">$</span><span class="pricing-amount">15</span><span class="pricing-period">/yr</span>
                        </div>
                        <p class="pricing-description">Save $9 compared to monthly — that's 3 months free.</p>
                    </div>
                    <a href="{{ route('subscription.create', 'annual') }}" class="btn btn-cta w-100 btn-lg mt-auto">
                        Subscribe
                    </a>
                </div>
            </div>

        </div>

        {{-- Fine print --}}
        <p class="text-center text-muted small mt-4">
            Payments are processed securely by <a href="https://stripe.com" target="_blank" rel="noopener">Stripe</a>.
            Subscriptions renew automatically and can be cancelled at any time from your account settings.
        </p>

    @endif

</div>

@endsection
