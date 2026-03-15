@extends('layouts.bootstrap')
@section('content')
    <div class="d-flex w-100 justify-content-between pb-3">
        <h1>Locations</h1>
        <form action="{{ route('locations.search') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="search" name="search" class="form-control" value="{{ old('search') }}" placeholder="Search for location" />
                <input type="submit" value="Search" class="btn btn-primary" />
            </div>
        </form>
        <p><a href="{{ route('locations.create') }}" class="btn btn-primary">New Location</a></p>
    </div>
    <div id="location-container">
        <locationsmap />
    </div>
@endsection
