@extends('layouts.bootstrap')

@section('full-content')

<div id="location-container">
    <form action="{{ route('checkins.store') }}" method="POST" class="checkin-page">
        @csrf

        @if ($errors->any())
            <div style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px;">
                <div class="alert alert-danger mb-0">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Left panel --}}
        <div class="checkin-panel">

            <div class="checkin-panel-header">
                <h5><i class="fa-solid fa-location-dot me-1" style="color: var(--ww-accent)"></i> New Check-in</h5>
                <a href="{{ route('checkins.index') }}" class="checkin-back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="checkin-form-scroll">

                <div class="checkin-field">
                    <label for="checkin_at">Date</label>
                    <input type="datetime-local" name="date" id="checkin_at" value="{{ old('date', now()->tz(auth()->user()->timezone)->format('Y-m-d\TH:i')) }}" placeholder="Now" />
                </div>

                {{-- Note --}}
                <div class="checkin-field">
                    <label>Note <span class="optional">— optional</span></label>
                    <textarea name="note" placeholder="Add a note about this visit…">{{ old('note') }}</textarea>
                </div>

            </div>

            <div class="checkin-footer">
                <button type="submit" class="btn-submit-checkin">
                    <i class="fa-solid fa-location-dot"></i> Check In
                </button>
            </div>

        </div>{{-- /checkin-panel --}}

        {{-- Right: map with inline location selector --}}
        <div class="checkin-map-col">
            <checkinmap :location="{{ $location ?? 'null' }}" :new_checkin="true" />
        </div>

    </form>
</div>

@endsection
