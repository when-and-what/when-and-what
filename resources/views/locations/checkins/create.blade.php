@extends('layouts.bootstrap')
@section('content')
    <h1>Checkin</h1>

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
                <checkinmap :location="{{ $location ?? 'null' }}" />
            </div>
            <div class="col-md">
                <div>
                    <label for="checkin_at">Date</label>
                    <input type="datetime-local" name="date" class="form-control" id="checkin_at" value="{{ old('date') }}" placeholder="Now" />
                </div>
                <div>
                    <label for="note">Note</label>
                    <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row text-center">
                <input type="submit" class="btn btn-primary" value="Checkin!" />
            </div>
        </div>
        </form>
    </div>
@endsection
