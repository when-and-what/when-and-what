@extends('layouts.bootstrap')

@section('content')
<div class="col-12">

    <div class="page-header mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Pending Check-ins</h1>
            <p class="page-subtitle">{{ $checkins->total() }} {{ Str::plural('check-in', $checkins->total()) }} waiting to be finalized</p>
        </div>
        <a href="{{ route('pending.create') }}" class="btn-submit-checkin text-decoration-none" style="width: auto; padding: 0.45rem 1.1rem; font-size: 0.85rem;">
            <i class="fa-solid fa-plus"></i> New Pending
        </a>
    </div>

    @if($checkins->isEmpty())
        <div class="content-card text-center py-5">
            <div style="font-size: 2rem; margin-bottom: 0.75rem;"><i class="fa-solid fa-circle-check" style="color: var(--ww-accent);"></i></div>
            <div style="font-weight: 600; color: var(--ww-dark); margin-bottom: 0.25rem;">All caught up</div>
            <div style="font-size: 0.875rem; color: var(--ww-muted);">No pending check-ins waiting to be finalized.</div>
        </div>
    @else
        <div class="d-flex flex-column gap-2">
            @foreach($checkins as $checkin)
            <a href="{{ route('pending.edit', $checkin) }}" class="content-card note-card text-decoration-none d-block">
                <div class="d-flex align-items-center gap-3">
                    <div class="pending-checkin-dot"></div>
                    <div class="flex-fill min-width-0">
                        <div class="d-flex align-items-baseline justify-content-between gap-2">
                            <span class="note-card-title">
                                {{ $checkin->name ?: $checkin->checkin_at->tz(Auth::user()->timezone)->toDayDateTimeString() }}
                            </span>
                            <span class="note-card-time">{{ $checkin->checkin_at->diffForHumans() }}</span>
                        </div>
                        @if($checkin->note)
                        <div class="note-card-sub mt-1">{{ Str::limit($checkin->note, 120) }}</div>
                        @endif
                    </div>
                    <i class="fa-solid fa-chevron-right" style="color: var(--ww-border); font-size: 0.75rem; flex-shrink: 0;"></i>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-3">{{ $checkins->links() }}</div>
    @endif

</div>
@endsection
