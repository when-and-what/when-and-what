@extends('layouts.bootstrap')

@section('content')

{{-- Page header --}}
<div class="col-12">
    <div class="location-show-header">

        <div class="location-show-title-row">
            <div class="location-show-title-group">
                @if($location->category->count())
                    <span class="location-show-categories">
                        @foreach($location->category as $cat){{ $cat->emoji }} @endforeach
                    </span>
                @endif
                <h1 class="location-show-name">{{ $location->name }}</h1>
            </div>
            <div class="location-show-actions">
                <a href="{{ route('checkins.create', $location) }}" class="btn-location-action btn-location-primary">
                    <i class="fa-solid fa-location-dot"></i> New Check-in
                </a>
                <a href="{{ route('locations.edit', $location) }}" class="btn-location-action btn-location-secondary">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <a href="{{ route('locations.create') }}?latitude={{ $location->latitude }}&longitude={{ $location->longitude }}" class="btn-location-action btn-location-ghost" title="Create a nearby location pre-filled with these coordinates">
                    <i class="fa-solid fa-copy"></i> Duplicate
                </a>
            </div>
        </div>

        <div class="location-show-meta">
            <span class="location-show-stat">
                <i class="fa-solid fa-location-dot"></i>
                {{ $checkinCount }} {{ Str::plural('check-in', $checkinCount) }}
            </span>
            @if($checkins->isNotEmpty())
                <span class="location-show-stat-sep">·</span>
                <span class="location-show-stat">
                    Last visit {{ $checkins->first()->checkin_at->tz(auth()->user()->timezone)->diffForHumans() }}
                </span>
            @endif
        </div>

    </div>
</div>

{{-- Recent check-ins --}}
<div class="col-12">
    <div class="location-checkins-card">

        <div class="location-checkins-card-header">
            <span class="location-checkins-card-title">Recent Check-ins</span>
            @if($checkinCount > 10)
                <span class="location-checkins-showing">Showing 10 of {{ $checkinCount }}</span>
            @endif
        </div>

        @if($checkins->isEmpty())
            <div class="location-checkins-empty">
                <i class="fa-solid fa-location-dot"></i>
                <p>No check-ins yet. <a href="{{ route('checkins.create', $location) }}">Add the first one.</a></p>
            </div>
        @else
            <div class="location-checkin-list">
                @foreach($checkins as $checkin)
                    <a href="{{ route('checkins.edit', $checkin) }}" class="location-checkin-item">
                        <div class="location-checkin-dot"></div>
                        <div class="location-checkin-body">
                            <span class="location-checkin-time">
                                {{ $checkin->checkin_at->tz(auth()->user()->timezone)->format('M j, Y') }}
                                <span class="location-checkin-time-sep">·</span>
                                {{ $checkin->checkin_at->tz(auth()->user()->timezone)->format('g:ia') }}
                            </span>
                            @if($checkin->note)
                                <span class="location-checkin-note">{{ $checkin->note }}</span>
                            @endif
                        </div>
                        <span class="location-checkin-ago">{{ $checkin->checkin_at->diffForHumans() }}</span>
                        <i class="fa-solid fa-chevron-right location-checkin-arrow"></i>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</div>

@endsection
