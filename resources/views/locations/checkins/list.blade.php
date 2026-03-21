@extends('layouts.bootstrap')

@section('content')

    {{-- Page header --}}
    <div class="col-12">
        <div class="page-header d-flex align-items-center justify-content-between">
            <div>
                <h1 class="page-title">Check-ins</h1>
                <p class="page-subtitle">Your location history</p>
            </div>
            <a href="{{ route('checkins.create') }}" class="btn-sm-action">
                <i class="fa-solid fa-plus"></i> New Check-in
            </a>
        </div>
    </div>

    {{-- Pending section --}}
    @if(count($pending) > 0)
    <div class="col-12">
        <div class="pending-section">
            <div class="pending-section-header">
                <i class="fa-solid fa-clock-rotate-left"></i>
                {{ count($pending) }} pending {{ Str::plural('check-in', count($pending)) }} need to be completed
            </div>
            <div class="pending-cards-row">
                @foreach($pending as $checkin)
                    <div class="pending-card">
                        <div class="pending-card-time">
                            <i class="fa-solid fa-clock me-1"></i>{{ $checkin->checkin_at->diffForHumans() }}
                        </div>
                        @if($checkin->note)
                            <div class="pending-card-note">{{ Str::limit($checkin->note, 60) }}</div>
                        @else
                            <div class="pending-card-note pending-card-note-empty">No note</div>
                        @endif
                        <a href="{{ route('pending.edit', $checkin) }}" class="pending-card-action">
                            Update <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Timeline --}}
    <div class="col-12">
        <div class="checkin-timeline">

            @forelse($checkins as $day => $checkinList)
                <div class="checkin-timeline-day">

                    <div class="checkin-timeline-date">
                        <span>{{ $day }}</span>
                    </div>

                    <div class="checkin-timeline-rows">
                        @foreach($checkinList as $checkin)
                            <div class="checkin-row">

                                <div class="checkin-row-icon">
                                    @php
                                        $firstEmoji = $checkin->location->category->first(fn($c) => $c->emoji)?->emoji;
                                    @endphp
                                    @if($firstEmoji)
                                        {{ $firstEmoji }}
                                    @else
                                        <i class="fa-solid fa-location-dot" style="font-size: 0.75rem;"></i>
                                    @endif
                                </div>

                                <div class="checkin-row-body">
                                    <a href="{{ route('locations.show', $checkin->location) }}" class="checkin-row-location">
                                        {{ $checkin->location->name }}
                                    </a>
                                    @if($checkin->note)
                                        <span class="checkin-row-note">{{ Str::limit($checkin->note, 80) }}</span>
                                    @endif
                                </div>

                                <a href="{{ route('checkins.edit', $checkin) }}" class="checkin-row-time">
                                    {{ $checkin->checkin_at->tz(Auth::user()->timezone)->format('g:i a') }}
                                </a>

                            </div>
                        @endforeach
                    </div>

                </div>
            @empty
                <div class="checkin-empty">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>No check-ins yet. Start by checking in to a location.</p>
                    <a href="{{ route('checkins.create') }}" class="btn-checkin btn-checkin-primary" style="width:auto; padding: 0.5rem 1.25rem; text-decoration:none;">
                        New Check-in
                    </a>
                </div>
            @endforelse

        </div>

        <div class="checkin-pagination">
            {!! $checkinLinks !!}
        </div>
    </div>

@endsection
