@extends('layouts.bootstrap')
@section('content')
    <h1>Checkin</h1>

    <div id="location-container">
        <form action="{{ route('checkins.destroy', $checkin) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="✖️" />
        </form>
        <form action="{{ route('checkins.update', $checkin) }}" method="POST">
            @method('PUT')
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <div class="row">
            <div class="col">
                <checkinmap :location="{{ $checkin->location }}" />
            </div>
            <div class="col">
                <div>
                    <label for="checkin_at">Date</label>
                    <input type="datetime-local" name="date" class="form-control" id="checkin_at" value="{{ old('date', $checkin->checkin_at->tz(Auth::user()->timezone)->format('Y-m-d H:i')) }}" placeholder="Now" />
                </div>
                <div>
                    <label for="note">Note</label>
                    <textarea name="note" id="note" class="form-control">{{ old('note', $checkin->note) }}</textarea>
                </div>
            </div>
        </div>
        <x-submit-button value="Update" />
        </form>
    </div>
@endsection
