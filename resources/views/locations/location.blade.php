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
                <newLocation locations draggable @if($location) latitude="{{ $location->latitude }}" longitude="{{ $location->longitude }}" @endif />
            </div>
            <div class="col">
                <div>
                    <label for="location-name">Name</label>
                    <input type="text" name="name" id="location-name" value="{{ old('name', optional($location)->name) }}" class="form-control" />
                </div>
                <div>
                    <label for="category">Category</label>
                    <select name="category[]" id="category" class="form-control" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if(in_array($category->id, $locationCategories)) selected="selected" @endif>{{ $category->emoji . ' '. $category->name }}</option>
                        @endforeach
                    </select>
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
