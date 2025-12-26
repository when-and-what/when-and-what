@extends('layouts.bootstrap')
@section('content')
    <h1>Pending Checking</h1>

    <div id="location-container">
        <form action="{{ route('pending.store') }}" method="POST">
            @csrf
        <div class="row">
            <div class="col">
                <newLocation draggable />
            </div>
            <div class="col">
                @foreach($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
                <div>
                    <label for="checkin_at">Date</label>
                    <input type="datetime-local" name="date" class="form-control" id="checkin_at" value="{{ old('date', now()->tz(auth()->user()->timezone)->format('Y-m-d\TH:i')) }}" placeholder="Now" />
                </div>
                <div>
                    <label for="location-name">Name</label>
                    <input type="text" name="name" id="location-name" value="{{ old('name') }}" class="form-control" />
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
