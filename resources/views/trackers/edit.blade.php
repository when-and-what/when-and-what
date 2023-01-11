@extends('layouts.bootstrap')

@section('content')
@if($tracker)
<form action="{{ route('trackers.update', $tracker) }}" method="POST">
    @method('PUT')
@else
<form action="{{ route('trackers.store') }}" method="POST">
@endif
    @csrf
<div class="d-flex">
    <div><h1>{{ $tracker ? 'Edit' : 'New' }} Tracker</h1></div>
    @if($tracker)
    <div class="flex-fill">
        <h3><a href="{{ route('trackers.create') }}" title="New Tracker" class="float-end">➕️</a></h3>
    </div>
    @endif
</div>
<div class="row">
    <div class="col">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" value="{{ old('name', $tracker?->name) }}" aria-describedby="name" />
            @error('name')
                <div id="name_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col">
        <div class="form-group">
            <label for="display_name">Display Name</label>
            <input type="text" class="form-control @if($errors->has('display_name')) is-invalid @endif" id="display_name" name="display_name" value="{{ old('display_name', $tracker?->display_name) }}" aria-describedby="display_name" />
            @error('display_name')
                <div id="display_name_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="form-group">
    <label for="icon">Icon</label>
    <input type="text" class="form-control @if($errors->has('icon')) is-invalid @endif" id="icon" name="icon" value="{{ old('icon', $tracker?->icon) }}" aria-describedby="icon" />
    @error('icon')
        <div id="icon_feedback" class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
<p><input type="submit" class="btn btn-primary" value="{{ $tracker ? 'Update' : 'Create' }}" /></p>
</form>
@endsection
