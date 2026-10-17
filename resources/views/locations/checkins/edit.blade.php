@extends('layouts.bootstrap')

@section('full-content')

<div id="location-container">

    {{-- Delete form (associated via form= attribute on the trash button) --}}
    <form id="delete-checkin-form" action="{{ route('checkins.destroy', $checkin) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <form action="{{ route('checkins.update', $checkin) }}" method="POST" class="checkin-page">
        @method('PUT')
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
                <h5><i class="fa-solid fa-location-dot me-1" style="color: var(--ww-accent)"></i> Edit Check-in</h5>
                <a href="{{ url()->previous() }}" class="checkin-back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="checkin-form-scroll">

                <div class="checkin-field">
                    <label for="checkin_at">Date</label>
                    <input type="datetime-local" name="date" id="checkin_at"
                           value="{{ old('date', $checkin->checkin_at->tz(Auth::user()->timezone)->format('Y-m-d\TH:i')) }}" />
                </div>

                <div class="checkin-field">
                    <label for="note">Note <span class="optional">— optional</span></label>
                    <textarea name="note" id="note" placeholder="Add a note about this visit…">{{ old('note', $checkin->note) }}</textarea>
                </div>

                <button type="submit" form="delete-checkin-form" class="checkin-delete-link"
                        onclick="return confirm('Delete this check-in?')">
                    <i class="fa-solid fa-trash-can"></i> Delete Checkin
                </button>

            </div>

            <div class="checkin-footer">
                <button type="submit" class="btn-submit-checkin">
                    <i class="fa-solid fa-floppy-disk"></i> Update Check-in
                </button>
            </div>

        </div>{{-- /checkin-panel --}}

        {{-- Right: map showing check-in location --}}
        <div class="checkin-map-col">
            <checkinmap :location="{{ $checkin->location }}" :zoom="18" />
        </div>

    </form>
</div>

@endsection
