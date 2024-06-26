@extends('layouts.bootstrap')
@section('content')
    <h1 class="d-flex justify-content-between">
        {{ $pending->name }}

        <form action="{{ route('pending.destroy', $pending) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="✖️" />
        </form>
    </h1>

    <div id="location-container">
        <form action="{{ route('pending.update', $pending) }}" method="POST">
            @method('PUT')
            @csrf
        <div class="row">
            <div class="col">
                <checkinmap locations latitude="{{ $pending->latitude }}" longitude="{{ $pending->longitude }}" draggable />
            </div>
            <div class="col">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="newlocation" role="switch" id="newlocation" v-model="newLocation" value="1" />
                    <label class="form-check-label" for="newlocation">New Location</label>
                </div>
                <div class="row" v-if="newLocation == '1'">
                    <div class="col-lg-6">
                        <label for="name">Location Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $pending->name) }}" />
                    </div>
                    <div class="col-lg-6">
                        <label for="category">Location Category</label>
                        <select name="category[]" class="form-control" id="category" multiple>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->emoji . ' '. $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="checkin_at">Date</label>
                    <input type="datetime-local" name="date" class="form-control" id="checkin_at" value="{{ $pending->checkin_at->tz(Auth::user()->timezone)->format('Y-m-d H:i') }}" />
                </div>
                <div>
                    <label for="note">Note</label>
                    <textarea name="note" id="note" class="form-control">{{ old('note', $pending->note) }}</textarea>
                </div>
            </div>
        </div>
        <x-submit-button value="Update" />
        </form>
    </div>
@endsection
