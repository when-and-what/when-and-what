@extends('layouts.bootstrap')

@section('content')
<div class="col-lg-8 col-xl-6">

    <div class="page-header mb-4">
        <a href="{{ url()->previous() }}" class="page-back-link mb-2">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <h1 class="page-title">Edit Play</h1>
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

    <form action="{{ route('podcasts.plays.update', $play) }}" method="POST">
        @method('PUT')
        @csrf

        <div class="content-card mb-3">

            <div class="mb-3">
                <div class="fw-semibold">{{ $play->episode->title }}</div>
                <div class="text-muted">{{ $play->episode->podcast->title }}</div>
            </div>

            <div class="field-group mb-3">
                <label class="field-label" for="played_at">Played At</label>
                <input type="datetime-local"
                       class="field-input{{ $errors->has('played_at') ? ' is-invalid' : '' }}"
                       id="played_at" name="played_at"
                       value="{{ old('played_at', $play->played_at->tz(Auth::user()->timezone)->format('Y-m-d\TH:i')) }}" />
                @error('played_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mt-4 pt-3" style="border-top: 1px solid var(--ww-border);">
                <a href="#"
                   class="delete-link"
                   onclick="event.preventDefault(); if(confirm('Delete this play?')) document.getElementById('delete-play-form').submit();">
                    <i class="fa-solid fa-trash-can me-1"></i> Delete this play
                </a>
            </div>

        </div>

        <button type="submit" class="btn-submit-checkin" style="width: auto; padding: 0.65rem 2rem;">
            <i class="fa-solid fa-floppy-disk"></i> Update Play
        </button>

    </form>

    <form id="delete-play-form" action="{{ route('podcasts.plays.destroy', $play) }}" method="POST" class="d-none">
        @method('DELETE')
        @csrf
    </form>

</div>
@endsection
