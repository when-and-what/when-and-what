@extends('layouts.bootstrap')

@section('content')
<div class="col-12">

    <div class="page-header mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Memories</h1>
            <p class="page-subtitle">{{ $memories->count() }} {{ Str::plural('memory', $memories->count()) }}</p>
        </div>
        <a href="{{ route('memories.create') }}" class="btn-submit-checkin text-decoration-none" style="width: auto; padding: 0.45rem 1.1rem; font-size: 0.85rem;">
            <i class="fa-solid fa-plus"></i> New Memory
        </a>
    </div>

    @if($memories->isEmpty())
        <div class="content-card text-center py-5">
            <div style="font-size: 2rem; margin-bottom: 0.75rem;">🗓️</div>
            <div style="font-weight: 600; color: var(--ww-dark); margin-bottom: 0.25rem;">No memories yet</div>
            <div style="font-size: 0.875rem; color: var(--ww-muted);">Save a date range to quickly look back on trips, events, or any span of time.</div>
        </div>
    @else
        <div class="d-flex flex-column gap-2">
            @foreach($memories as $memory)
            <div class="content-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="flex-fill min-width-0">
                        <div class="d-flex align-items-baseline justify-content-between gap-2 mb-1">
                            <a href="{{ route('memories.show', $memory) }}" class="note-card-title">{{ $memory->name }}</a>
                            <span class="note-card-time">{{ $memory->start_date->toFormattedDateString() }} – {{ $memory->end_date->toFormattedDateString() }}</span>
                        </div>
                        @if($memory->tags->isNotEmpty())
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach($memory->tags as $tag)
                            <span class="badge" style="background: var(--ww-accent-light); color: var(--ww-accent); font-weight: 500;">
                                {{ $tag->icon }} {{ $tag->display_name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <a href="{{ route('memories.edit', $memory) }}" class="note-card-edit" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
