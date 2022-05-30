@extends('layouts.bootstrap')
@section('content')
    <h1>New Location</h1>

    <div id="location-container">
        <form action="{{ route('locations.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col">
                    <newLocation locations draggable latitude="{{ $latitude }}" longitude="{{ $longitude }}"/>
                </div>
                <div class="col">
                    <div>
                        <label for="location-name">Name</label>
                        <input type="text" name="name" id="location-name" value="{{ old('name') }}" class="form-control" />
                    </div>
                    <div>
                        <label for="category">Category</label>
                        <select name="category[]" id="category" class="form-control" multiple>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->emoji . ' '. $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row text-center">
                    <input type="submit" class="btn btn-primary" value="Create" />
                </div>
            </div>
        </form>
    </div>
@endsection
