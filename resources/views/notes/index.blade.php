@extends('layouts.bootstrap')

@section('content')
<div class="col-12">

    <div class="page-header mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Notes</h1>
            <p class="page-subtitle">{{ $notes->count() }} {{ Str::plural('note', $notes->count()) }}</p>
        </div>
        <a href="{{ route('notes.create') }}" class="btn-submit-checkin text-decoration-none" style="width: auto; padding: 0.45rem 1.1rem; font-size: 0.85rem;">
            <i class="fa-solid fa-plus"></i> New Note
        </a>
    </div>

    @if($notes->isEmpty())
        <div class="content-card text-center py-5">
            <div style="font-size: 2rem; margin-bottom: 0.75rem;">📝</div>
            <div style="font-weight: 600; color: var(--ww-dark); margin-bottom: 0.25rem;">No notes yet</div>
            <div style="font-size: 0.875rem; color: var(--ww-muted);">Notes you create will appear here.</div>
        </div>
    @else
        <div class="d-flex flex-column gap-2">
            @foreach($notes as $note)
            <div class="content-card note-card">
                <div class="d-flex align-items-start gap-3">
                    @if($note->icon)
                    <div class="note-card-icon">{{ $note->icon }}</div>
                    @endif
                    <div class="flex-fill min-width-0">
                        <div class="d-flex align-items-baseline justify-content-between gap-2 mb-1">
                            <a href="{{ route('notes.show', $note) }}" class="note-card-title">{{ $note->title }}</a>
                            <span class="note-card-time">{{ $note->published_at->diffForHumans() }}</span>
                        </div>
                        @if($note->sub_title)
                        <div class="note-card-sub">{{ $note->sub_title }}</div>
                        @endif
                        @if($note->note)
                        <div class="note-card-body">{{ Str::limit($note->note, 160) }}</div>
                        @endif
                    </div>
                    <a href="{{ route('notes.edit', $note) }}" class="note-card-edit" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
