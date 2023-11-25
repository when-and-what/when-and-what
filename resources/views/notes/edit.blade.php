@extends('layouts.bootstrap')

@section('content')
<div class="d-flex">
    <div><h1>{{ $note ? 'Edit' : 'New' }} Note</h1></div>
    @if($note)
    <div class="flex-fill">
        <form action="{{ route('notes.destroy', $note) }}" method="POST">
            @method('DELETE')
            @csrf
            <h3><button title="Delete Note" class="float-end btn btn-danger">X</button></h3>
        </form>
    </div>
    @endif
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
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
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" id="title" name="title" value="{{ old('title', $note?->title) }}" aria-describedby="title" />
            @error('title')
                <div id="title_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="icon">Icon</label>
            <input type="text" class="form-control @if($errors->has('icon')) is-invalid @endif" id="icon" name="icon" value="{{ old('icon', $note?->icon) }}" aria-describedby="icon" />
            @error('icon')
                <div id="icon_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="sub_title">Sub Title</label>
            <input type="text" class="form-control @if($errors->has('sub_title')) is-invalid @endif" id="sub_title" name="sub_title" value="{{ old('sub_title', $note?->sub_title) }}" aria-describedby="sub_title" />
            @error('sub_title')
                <div id="sub_title_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="published_at">Date</label>
            <input type="datetime-local" name="published_at" class="form-control" id="checkin_at" value="{{ old('published_at', $note?->published_at->tz(Auth::user()->timezone)->format('Y-m-d H:i')) }}" placeholder="Now" />
            @error('published_at')
                <div id="published_at_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="form-group">
    <label for="note">Note</label>
    <textarea class="form-control @if($errors->has('note')) is-invalid @endif" id="note" name="note" aria-describedby="note">{{ old('note', $note?->note )}}</textarea>
    @error('note')
        <div id="note_feedback" class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="dashboard_visible" value="1" role="switch" id="dashboard_visible" @checked($note ? $note->dashboard_visible: false)>
    <label class="form-check-label" for="dashboard_visible">Dashboard</label>
</div>
<p><input type="submit" class="btn btn-primary" value="{{ $note ? 'Update' : 'Create' }}" /></p>
</form>
@endsection
