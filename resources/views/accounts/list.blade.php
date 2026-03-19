@extends('layouts.bootstrap')

@section('content')

<div class="col-12">
    <div class="page-header">
        <h1 class="page-title">Integrations</h1>
        <p class="page-subtitle">Connect your services to start seeing them in your daily summary.</p>
    </div>

    <div class="row g-3">
        @foreach($accounts as $account)
            @if(!$account->admin_only || in_array(auth()->user()->email, config('auth.admin_emails')))
                @php $connected = count($account->users) > 0; @endphp
                <div class="col-sm-6 col-lg-4">
                    <div class="integration-card-app {{ $connected ? 'is-connected' : '' }}">
                        <div class="integration-card-app-body">
                            <div class="integration-name">{{ $account->name }}</div>
                            <div class="integration-status">
                                @if($connected)
                                    <span class="status-badge status-connected">
                                        <i class="fa-solid fa-circle-check"></i> Connected
                                    </span>
                                @else
                                    <span class="status-badge status-disconnected">Not connected</span>
                                @endif
                            </div>
                        </div>
                        <div class="integration-card-app-footer">
                            @if($connected)
                                <form action="{{ route('accounts.destroy', $account) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-integration btn-integration-disconnect">
                                        Disconnect
                                    </button>
                                </form>
                            @else
                                @if($account->edit_username || $account->edit_token)
                                    <a href="{{ route('accounts.edit', $account) }}" class="btn-integration btn-integration-connect">
                                        Connect
                                    </a>
                                @else
                                    <a href="{{ route('socialite.redirect', $account) }}" class="btn-integration btn-integration-connect">
                                        Connect
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

@endsection
