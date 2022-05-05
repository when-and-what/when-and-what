@extends('layouts.bootstrap')
@section('content')
    <h1>{{ $category ? 'Edit' : 'New' }} Category</h1>

    <div id="location-container">
        @if($category)
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @method('PUT')
        @else
            <form action="{{ route('categories.store') }}" method="POST">
        @endif
            @csrf
            @foreach($errors->all() as $message)
                <p>{{ $message }}</p>
            @endforeach
                <div>
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', optional($category)->name) }}" />
                </div>
                <div>
                    <label for="emoji">Emoji</label>
                    <input type="text" name="emoji" class="form-control" value="{{ old('emoji', optional($category)->emoji) }}" />
                </div>

                <p><input type="submit" class="btn btn-primary" value="{{ $category ? 'Update' : 'Create' }}" /></p>
        </form>
    </div>
@endsection
