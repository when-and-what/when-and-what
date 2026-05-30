@extends('layouts.bootstrap')

@section('content')
<div class="col-12">

    <div class="page-header mb-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <div>
            <h1 class="page-title">Trackers</h1>
            <p class="page-subtitle">{{ $trackers->count() }} {{ Str::plural('tracker', $trackers->count()) }}</p>
        </div>
        <a href="{{ route('trackers.create') }}" class="btn-submit-checkin text-decoration-none" style="width: auto; padding: 0.45rem 1rem; font-size: 0.85rem;">
            <i class="fa-solid fa-plus"></i> New Tracker
        </a>
    </div>

    @if($trackers->isEmpty())
        <div class="content-card text-center py-5">
            <div style="font-size: 2rem; margin-bottom: 0.75rem;">📊</div>
            <div style="font-weight: 600; color: var(--ww-dark); margin-bottom: 0.25rem;">No trackers yet</div>
            <div style="font-size: 0.875rem; color: var(--ww-muted);">Create your first tracker to start logging data.</div>
        </div>
    @else
        <div class="row g-3">
            @foreach($trackers as $tracker)
            <div class="col-sm-6 col-lg-4">
                <div class="location-list-card">
                    <a href="{{ route('trackers.show', $tracker) }}" class="location-list-card-body">
                        <div class="location-list-card-icon">
                            {{ $tracker->icon ?: $tracker->unit->type()->emoji() }}
                        </div>
                        <div class="location-list-card-name">{{ $tracker->name }}</div>
                    </a>
                    <div class="location-list-card-footer">
                        <span class="location-list-visits">
                            {{ $tracker->unit->type()->emoji() }} {{ $tracker->unit->label() }}
                        </span>
                        <a href="{{ route('trackers.edit', $tracker) }}" class="location-list-edit" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
