@extends('layouts.bootstrap')
@section('content')
    <h1>New Location</h1>

    <div id="location-container">
        @if($location)
            <form action="{{ route('locations.update', $location) }}" method="POST">
                @method('PUT')
        @else
            <form action="{{ route('locations.store') }}" method="POST">
        @endif
            @csrf
        <div class="row">
            <div class="col">
                <newLocation locations draggable latitude="{{ optional($location)->latitude }}" longitude="{{ optional($location)->longitude }}" />
            </div>
            <div class="col">
                <div>
                    <label for="location-name">Name</label>
                    <input type="text" name="name" id="location-name" value="{{ old('name', optional($location)->name) }}" class="form-control" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row text-center">
                <input type="submit" class="btn btn-primary" value="{{ $location ? 'Update' : 'Create' }}" />
            </div>
        </div>
        </form>
    </div>
@endsection
