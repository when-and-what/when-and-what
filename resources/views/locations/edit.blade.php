@extends('layouts.bootstrap')
@section('content')
    <h1>Edit Location</h1>

    <div id="location-container">
        <form action="{{ route('locations.update', $location) }}" method="POST">
            @method('PUT')
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
            <x-submit-button value="Update" />
        </form>
    </div>
@endsection
