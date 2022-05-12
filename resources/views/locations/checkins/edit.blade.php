@extends('layouts.bootstrap')
@section('content')
    <h1>Checkin</h1>

    <div id="location-container">
        @if($checkin)
            <form action="{{ route('checkins.destroy', $checkin) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" class="btn btn-danger" value="✖️" />
            </form>
            <form action="{{ route('checkins.update', $checkin) }}" method="POST">
                @method('PUT')
        @else
            <form action="{{ route('checkins.store') }}" method="POST">
        @endif
            @csrf
        <div class="row">
            <div class="col">
                <checkinmap :location="{{ optional($checkin)->location }}" />
            </div>
            <div class="col">
                <div>
                    <label for="checkin_at">Date</label>
                    <input type="datetime-local" name="date" class="form-control" id="checkin_at" value="{{ old('date', $checkin ? $checkin->checkin_at->format('Y-m-d H:i') : '') }}" placeholder="Now" />
                </div>
                <div>
                    <label for="note">Note</label>
                    <textarea name="note" id="note" class="form-control">{{ old('note', optional($checkin)->note) }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row text-center">
                <input type="submit" class="btn btn-primary" value="{{ $checkin ? 'Update' : 'Create' }}" />
            </div>
        </div>
        </form>
    </div>
@endsection
