@extends('layouts.bootstrap')

@section('content')

<div class="col-12">
    <div class="page-header">
        <a href="{{ route('accounts.index') }}" class="page-back-link">
            <i class="fa-solid fa-chevron-left"></i> Integrations
        </a>
        <h1 class="page-title mt-2">Connect {{ $account->name }}</h1>
    </div>

    <div class="row g-4">

        {{-- Credentials form --}}
        <div class="col-md-5">
            <div class="content-card">
                <form action="{{ route('accounts.update', $account) }}" method="POST">
                    @method('PUT')
                    @csrf

                    @if($account->edit_username)
                        <div class="field-group">
                            <label class="field-label" for="username">Username</label>
                            <input type="text"
                                   class="field-input @if($errors->has('username')) is-invalid @endif"
                                   id="username" name="username" value="{{ old('username') }}" />
                            @if($errors->has('username'))
                                <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                            @endif
                        </div>
                    @endif

                    @if($account->edit_token)
                        <div class="field-group">
                            <label class="field-label" for="token">Token</label>
                            <input type="text"
                                   class="field-input @if($errors->has('token')) is-invalid @endif"
                                   id="token" name="token" value="{{ old('token') }}" />
                            @if($errors->has('token'))
                                <div class="invalid-feedback">{{ $errors->first('token') }}</div>
                            @endif
                        </div>
                    @endif

                    <button type="submit" class="btn-checkin btn-checkin-primary mt-3">Save</button>
                </form>
            </div>
        </div>

        {{-- PocketCasts instructions --}}
        @if($account->slug === 'pocketcasts')
            <div class="col-md-7" id="pocketcasts-account">
                <div class="content-card">
                    <h5 class="content-card-title">How to get your token</h5>
                    <p class="text-muted small">To connect <a href="https://pocketcasts.com/" target="_blank">PocketCasts</a> we need an API access token for your account.</p>

                    <ol class="instructions-list">
                        <li>
                            Open a terminal or use a tool like <a href="https://httpie.io/app" target="_blank">HTTPie</a>.
                            <div class="info-box info-box-warning mt-2">
                                If you're not familiar with the terminal, use HTTPie. Pasting commands you don't understand is a security risk.
                            </div>
                        </li>
                        <li>
                            Enter your email and password below and click <strong>Update curl command</strong> — your credentials will appear in the command above the form.
                            <div class="info-box mt-2">
                                Your email and password are not sent or saved — they only update the curl command for easy copy/paste.
                            </div>
                        </li>
                        <li>Paste the command into your terminal or HTTPie (in HTTPie: click <strong>Import → Text</strong>).</li>
                        <li>
                            Copy the <code>token</code> value from the response and paste it into the form on the left.
                            <div class="info-box info-box-warning mt-2">
                                Keep this token private. Anyone with it can access your PocketCasts account. When and What only uses it to read your listening history.
                            </div>
                        </li>
                    </ol>

                    <div class="curl-command">
                        curl -H "Content-Type: application/json" -H "Accept: application/json" -d '{"email": "<span id="pocketcasts-email"></span>", "password":"<span id="pocketcasts-password"></span>"}' https://api.pocketcasts.com/user/login
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-sm-6">
                            <div class="field-group">
                                <label class="field-label" for="email">Email</label>
                                <input type="email" class="field-input" id="email" name="email" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="field-group">
                                <label class="field-label" for="password">Password</label>
                                <input type="password" class="field-input" id="password" name="password" />
                            </div>
                        </div>
                    </div>
                    <button id="generate-token" class="btn-checkin btn-checkin-secondary mt-3">Update curl command</button>
                </div>
            </div>
        @endif

    </div>
</div>

@endsection
