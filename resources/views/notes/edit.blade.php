@extends('layouts.bootstrap')

@section('content')
<div class="col-lg-8 col-xl-6">

    <div class="page-header mb-4">
        <a href="{{ url()->previous() }}" class="page-back-link mb-2">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <h1 class="page-title">{{ $note ? 'Edit Note' : 'New Note' }}</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($note)
    <form action="{{ route('notes.update', $note) }}" method="POST">
        @method('PUT')
    @else
    <form action="{{ route('notes.store') }}" method="POST">
    @endif
        @csrf

        <div class="content-card mb-3">

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <div class="field-group mb-0">
                        <label class="field-label" for="title">Title</label>
                        <input type="text"
                               class="field-input{{ $errors->has('title') ? ' is-invalid' : '' }}"
                               id="title" name="title"
                               value="{{ old('title', $note?->title) }}" />
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-group mb-0">
                        <label class="field-label" for="icon">Icon <span class="field-optional">— optional</span></label>
                        <input type="text"
                               class="field-input{{ $errors->has('icon') ? ' is-invalid' : '' }}"
                               id="icon" name="icon"
                               value="{{ old('icon', $note?->icon) }}"
                               placeholder="e.g. 🎵" />
                        @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <div class="field-group mb-0">
                        <label class="field-label" for="sub_title">Sub Title <span class="field-optional">— optional</span></label>
                        <input type="text"
                               class="field-input{{ $errors->has('sub_title') ? ' is-invalid' : '' }}"
                               id="sub_title" name="sub_title"
                               value="{{ old('sub_title', $note?->sub_title) }}" />
                        @error('sub_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-group mb-0">
                        <label class="field-label" for="published_at">Date &amp; Time</label>
                        <input type="datetime-local"
                               class="field-input{{ $errors->has('published_at') ? ' is-invalid' : '' }}"
                               id="published_at" name="published_at"
                               value="{{ old('published_at', $note
                                   ? $note->published_at->tz(Auth::user()->timezone)->format('Y-m-d\TH:i')
                                   : now(Auth::user()->timezone)->format('Y-m-d\TH:i')) }}" />
                        @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="field-group mb-3">
                <label class="field-label" for="note">Note <span class="field-optional">— optional</span></label>
                <textarea class="field-input{{ $errors->has('note') ? ' is-invalid' : '' }}"
                          id="note" name="note"
                          rows="5"
                          style="height: auto; resize: vertical;">{{ old('note', $note?->note) }}</textarea>
                @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="dashboard_visible"
                       value="1" role="switch" id="dashboard_visible"
                       @checked($note ? $note->dashboard_visible : false)>
                <label class="form-check-label" for="dashboard_visible">Show on dashboard</label>
            </div>

            @if($note)
            <div class="mt-4 pt-3" style="border-top: 1px solid var(--ww-border);">
                <a href="#"
                   class="delete-link"
                   onclick="event.preventDefault(); if(confirm('Delete this note?')) document.getElementById('delete-note-form').submit();">
                    <i class="fa-solid fa-trash-can me-1"></i> Delete this note
                </a>
            </div>
            @endif

        </div>

        <button type="submit" class="btn-submit-checkin" style="width: auto; padding: 0.65rem 2rem;">
            <i class="fa-solid fa-{{ $note ? 'floppy-disk' : 'plus' }}"></i>
            {{ $note ? 'Update Note' : 'Create Note' }}
        </button>

    </form>

    @if($note)
    <form id="delete-note-form" action="{{ route('notes.destroy', $note) }}" method="POST" class="d-none">
        @method('DELETE')
        @csrf
    </form>
    @endif

</div>
@endsection
