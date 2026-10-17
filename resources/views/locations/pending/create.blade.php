@extends('layouts.bootstrap')

@section('full-content')

<div id="location-container">
    <form action="{{ route('pending.store') }}" method="POST" class="checkin-page">
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
                <h5><i class="fa-solid fa-bookmark me-1" style="color: var(--ww-accent)"></i> Save for Later</h5>
                <a href="{{ url()->previous() }}" class="checkin-back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="checkin-form-scroll">

                <p style="font-size: 0.8rem; color: var(--ww-muted); margin: 0;">
                    Drop a pin on the map and save it as a pending check-in to finalize the location name later.
                </p>

                {{-- Date --}}
                <div class="checkin-field">
                    <label for="checkin_at">Date &amp; Time</label>
                    <input type="datetime-local" name="date" id="checkin_at" class="field-input"
                           value="{{ old('date', now()->tz(auth()->user()->timezone)->format('Y-m-d\TH:i')) }}" />
                </div>

                {{-- Name --}}
                <div class="checkin-field">
                    <label for="location-name">Name <span class="field-optional">— optional</span></label>
                    <input type="text" name="name" id="location-name" class="field-input"
                           value="{{ old('name') }}" placeholder="Leave blank to name it later…" />
                </div>

                {{-- Note --}}
                <div class="checkin-field">
                    <label for="note">Note <span class="field-optional">— optional</span></label>
                    <textarea name="note" id="note" class="field-input"
                              style="height: 80px; resize: none; line-height: 1.5;"
                              placeholder="Any notes about this spot…">{{ old('note') }}</textarea>
                </div>

            </div>

            <div class="checkin-footer">
                <button type="submit" class="btn-submit-checkin">
                    <i class="fa-solid fa-bookmark"></i> Save Pending
                </button>
            </div>

        </div>{{-- /checkin-panel --}}

        {{-- Right: map --}}
        <div class="checkin-map-col">
            <newlocation :draggable="true" />
        </div>

    </form>
</div>

@endsection
