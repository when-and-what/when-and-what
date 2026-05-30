@extends('layouts.bootstrap')

@push('styles')
<style>
.event-delete-btn { font-size: 0.78rem; color: var(--ww-border); cursor: pointer; transition: color 0.15s; }
.event-delete-btn:hover { color: #dc3545; }
</style>
@endpush

@section('content')
<div class="col-12">

    <div class="page-header">
        <a href="{{ route('trackers.index') }}" class="page-back-link mb-2">
            <i class="fa-solid fa-arrow-left"></i> Trackers
        </a>
        <div class="location-show-title-row mb-0">
            <div class="location-show-title-group">
                @if($tracker->icon)
                    <div class="location-show-categories">{{ $tracker->icon }}</div>
                @endif
                <h1 class="location-show-name">{{ $tracker->name }}</h1>
            </div>
            <div class="location-show-actions">
                <a href="{{ route('trackers.edit', $tracker) }}" class="btn-location-action btn-location-secondary">
                    <i class="fa-solid fa-pencil"></i> Edit
                </a>
                <form action="{{ route('trackers.destroy', $tracker) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-location-action btn-location-ghost"
                            onclick="return confirm('Delete this tracker and all its events?')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <div class="location-show-meta">
                <span class="location-show-stat">
                    <i class="fa-solid fa-hashtag"></i> {{ $tracker->code }}
                </span>
                <span class="location-show-stat-sep">·</span>
                <span class="location-show-stat">
                    {{ $tracker->unit->type()->emoji() }} {{ $tracker->unit->plural() }}
                </span>
                @if(1 == 2 && $tracker->color)
                    <span class="location-show-stat-sep">·</span>
                    <span class="location-show-stat">
                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $tracker->color }};border:1px solid var(--ww-border);"></span>
                        {{ $tracker->color }}
                    </span>
                @endif
            </div>

            <form action="{{ route('trackers.events.store', $tracker) }}" method="POST"
                class="d-flex mt-1 gap-2 justify-content-end">
                @csrf
                <input type="number" name="event_value" step="any"
                    class="form-control @error('event_value') is-invalid @enderror"
                    placeholder="{{ $tracker->unit->symbol() ?: $tracker->unit->plural() }}"
                    value="{{ old('event_value') }}"
                    style="width:120px;"
                    required>

                <input type="datetime-local" name="event_time"
                    class="form-control @error('event_time') is-invalid @enderror"
                    value="{{ old('event_time', now()->tz(auth()->user()->timezone)->format('Y-m-d\TH:i')) }}"
                    required>

                <button type="submit" class="btn-sm-action">
                    <i class="fa-solid fa-plus"></i> Log
                </button>
            </form>
        </div>
    </div>

    <div class="location-checkins-card">
        <div class="location-checkins-card-header">
            <span class="location-checkins-card-title">Events</span>
            <span class="location-checkins-showing">{{ $events->total() }} total</span>
        </div>
        <div class="location-checkin-list">
            @forelse($events as $event)
                <div class="checkin-row">
                    <div class="checkin-row-icon">
                        {{ $tracker->icon ?: $tracker->unit->type()->emoji() }}
                    </div>
                    <div class="checkin-row-body">
                        <span class="checkin-row-location">
                            {{ (float) $event->event_value }}
                            @if($tracker->unit->symbol())
                                {{ $tracker->unit->symbol() }}
                            @else
                                @if($event->event_value > 1)
                                    {{ $tracker->unit->plural() }}
                                @else
                                    {{ $tracker->unit->label() }}
                                @endif
                            @endif
                        </span>
                        @if($event->notes)
                            <span class="checkin-row-note">{{ $event->notes }}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <form action="{{ route('trackers.events.destroy', [$tracker, $event]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="event-delete-btn border-0 bg-transparent p-0"
                                    onclick="return confirm('Delete this event?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        <span class="checkin-row-time">
                            {{ $event->event_time->format('M j, Y') }}<br>
                            @unless($event->all_day)
                                {{ $event->event_time->format('g:i A') }}
                            @endunless
                        </span>
                    </div>
                </div>
            @empty
                <div class="checkin-empty">
                    <i class="fa-regular fa-chart-bar"></i>
                    <p>No events logged yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    @if($events->hasPages())
        <div class="checkin-pagination">
            {{ $events->links() }}
        </div>
    @endif

</div>
@endsection
