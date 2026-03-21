@extends('layouts.bootstrap')

@section('full-content')

{{-- Delete form lives outside the main form to avoid nesting --}}
<form id="delete-pending-form" action="{{ route('pending.destroy', $pending) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<div id="location-container">
    <form action="{{ route('pending.update', $pending) }}" method="POST" class="checkin-page">
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
                <h5><i class="fa-solid fa-bookmark me-1" style="color: var(--ww-accent)"></i> Finalize Check-in</h5>
                <a href="{{ url()->previous() }}" class="checkin-back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="checkin-form-scroll">

                {{-- Date & Time --}}
                <div class="checkin-field">
                    <label for="checkin_at">Date &amp; Time</label>
                    <input type="datetime-local" name="date" id="checkin_at" class="field-input"
                           value="{{ old('date', $pending->checkin_at->tz(Auth::user()->timezone)->format('Y-m-d\TH:i')) }}" />
                </div>

                {{-- New location group --}}
                <div class="new-location-group">
                    <label class="pending-toggle" for="new-location-check">
                        <input class="form-check-input" type="checkbox" name="newlocation" id="new-location-check"
                               value="1" role="switch" v-model="newLocation" />
                        <span>Create a new location</span>
                    </label>

                    <div v-if="newLocation == '1'" class="new-location-fields">
                        <div class="checkin-field mb-3">
                            <label for="name">Location Name</label>
                            <input type="text" name="name" id="name" class="field-input"
                                   value="{{ old('name', $pending->name) }}" placeholder="e.g. Corner Café" />
                        </div>
                        <div class="checkin-field">
                            <label for="category">Category <span class="field-optional">— optional</span></label>
                            <select name="category[]" class="field-input" id="category" multiple
                                    style="height: auto; padding: 0.3rem 0.5rem;">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->emoji . ' ' . $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Note --}}
                <div class="checkin-field">
                    <label for="note">Note <span class="field-optional">— optional</span></label>
                    <textarea name="note" id="note" class="field-input"
                              style="height: 80px; resize: none; line-height: 1.5;"
                              placeholder="Any notes about this spot…">{{ old('note', $pending->note) }}</textarea>
                </div>

                {{-- Delete --}}
                <div class="pt-2" style="border-top: 1px solid var(--ww-border); margin-top: auto;">
                    <button type="button" class="btn-delete-link"
                            onclick="if(confirm('Delete this pending check-in?')) document.getElementById('delete-pending-form').submit()">
                        <i class="fa-solid fa-trash-can me-1"></i> Delete this pending check-in
                    </button>
                </div>

            </div>{{-- /checkin-form-scroll --}}

            <div class="checkin-footer">
                <button type="submit" class="btn-submit-checkin">
                    <i class="fa-solid fa-circle-check"></i> Finalize Check-in
                </button>
            </div>

        </div>{{-- /checkin-panel --}}

        {{-- Right: map centered on the pending pin --}}
        <div class="checkin-map-col">
            <checkinmap :latitude="{{ $pending->latitude }}" :longitude="{{ $pending->longitude }}" :new_checkin="false" />
        </div>

    </form>
</div>

@endsection
