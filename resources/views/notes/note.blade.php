@extends('layouts.bootstrap')

@section('content')
<div class="col-lg-8 col-xl-6">

    <div class="page-header mb-4">
        <a href="{{ url()->previous() }}" class="page-back-link mb-2">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <h1 class="page-title d-flex align-items-center gap-2">
            @if($note->icon)<span>{{ $note->icon }}</span>@endif
            {{ $note->title }}
        </h1>
        @if($note->sub_title)
        <p class="page-subtitle">{{ $note->sub_title }}</p>
        @endif
    </div>

    <div class="content-card">
        <div class="d-flex align-items-center justify-content-between mb-3" style="border-bottom: 1px solid var(--ww-border); padding-bottom: 0.875rem;">
            <span style="font-size: 0.8rem; color: var(--ww-muted);">
                <i class="fa-solid fa-calendar me-1"></i>
                {{ $note->published_at->tz(Auth::user()->timezone)->format('F j, Y \a\t g:ia') }}
            </span>
            <a href="{{ route('notes.edit', $note) }}" class="page-back-link">
                <i class="fa-solid fa-pen me-1"></i> Edit
            </a>
        </div>
        @if($note->note)
        <div class="note-body">{{ $note->note }}</div>
        @else
        <div style="color: var(--ww-muted); font-size: 0.875rem; font-style: italic;">No content.</div>
        @endif
    </div>

</div>
@endsection
