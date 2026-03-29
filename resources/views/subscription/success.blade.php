@extends('layouts.bootstrap')

@section('full-content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-4 text-center">
            <div style="font-size: 3rem; color: var(--ww-accent); margin-bottom: 1rem;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h1 class="page-title">You're all set!</h1>
            <p class="page-subtitle mt-3">Thanks for subscribing to When and What. Your account is now active and all features are unlocked.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-lg mt-4" style="background: var(--ww-accent); border-color: var(--ww-accent); color: #fff; font-weight: 600;">
                Go to Dashboard
            </a>
            <p class="text-muted small mt-4">
                Have questions? <a href="https://whenandwhat.me/contact">Contact us</a> and we'll be happy to help.
            </p>
        </div>
    </div>
</div>

@endsection
