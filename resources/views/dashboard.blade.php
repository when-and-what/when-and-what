@extends('layouts.bootstrap')
@section('content')
<div class="d-flex justify-content-between">
    <div>
        <h2><a href="{{ route('checkins.index') }}">Checkins</a></h2>
        <a href="{{ route('checkins.create') }}" class="btn btn-primary">Check-in</a>
    </div>
    <div>
        <h2><a href="{{ route('locations.index') }}">Locations</a></h2>
        <a href="{{ route('locations.create') }}" class="btn btn-primary">New Location</a>
    </div>
    <div>
        <h2><a href="{{ route('categories.index') }}">Categories</a></h2>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">New Category</a>
</div>
@endsection
