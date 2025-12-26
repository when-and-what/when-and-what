@extends('layouts.bootstrap')
@section('content')
    <div class="d-flex">
        <div><h1>Checkin</h1></div>
    </div>

    <div id="location-container">
        <form action="{{ route('checkins.store') }}" method="POST">
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
            <div class="col-md">
                <checkinmap :location="{{ $location ?? 'null' }}" :new_checkin="true" />
            </div>
            <div class="col-md">
                <div>
                    <label for="checkin_at">Date</label>
                    <input type="datetime-local" name="date" class="form-control" id="checkin_at" value="{{ old('date', now()->tz(auth()->user()->timezone)->format('Y-m-d\TH:i')) }}" placeholder="Now" />
                </div>
                <div>
                    <label for="note">Note</label>
                    <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
                </div>
            </div>
        </div>
        <x-submit-button value="Checkin!" />
        </form>
    </div>
@endsection
