@extends('layouts.bootstrap')
@section('content')
    <h1>{{ $pending->name }}</h1>

    <div id="location-container">
        <form action="{{ route('pending.update', $pending) }}" method="POST">
            @csrf
        <div class="row">
            <div class="col">
                <checkinmap locations latitude="{{ $pending->latitude }}" longitude="{{ $pending->longitude }}" />
            </div>
            <div class="col">
                <div>
                    <label for="checkin_at">Date</label>
                    <input type="datetime-local" name="date" class="form-control" id="checkin_at" value="{{ old('date', $pending->checkin_atcojj ? $pending->checkin_at->format('Y-m-d H:i') : '') }}" placeholder="Now" />
                </div>
                <div>
                    <label for="note">Note</label>
                    <textarea name="note" id="note" class="form-control">{{ old('note', optional($pending)->note) }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row text-center">
                <input type="submit" class="btn btn-primary" value="Update" />
            </div>
        </div>
        </form>
    </div>
@endsection
