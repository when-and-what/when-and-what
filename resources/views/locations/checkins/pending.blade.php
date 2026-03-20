@extends('layouts.bootstrap')

@section('full-content')

<div id="location-container">
    <div class="checkin-page">

        {{-- Left panel --}}
        <div class="checkin-panel">

            <div class="checkin-panel-header">
                <h5><i class="fa-solid fa-location-dot me-1" style="color: var(--ww-accent)"></i> New Location</h5>
                <a href="{{ url()->previous() }}" class="checkin-back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="checkin-form-scroll">

                <p style="font-size: 0.8rem; color: var(--ww-muted); margin: 0;">
                    Drop a pin on the map, then name the location or link it to an existing one.
                </p>

                {{-- Tabs --}}
                <div>
                    <ul class="nav nav-tabs nav-fill mb-3" role="tablist" style="font-size: 0.82rem;">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" data-bs-toggle="tab" data-bs-target="#new-location"
                               role="tab" aria-controls="new-location" aria-selected="true">New Location</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#select-location"
                               role="tab" aria-controls="select-location">Select Existing</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="new-location" class="tab-pane show active" role="tabpanel">
                            <div class="checkin-field mb-3">
                                <label for="location-name">Location Name</label>
                                <input type="text" name="name" id="location-name" class="field-input"
                                       placeholder="e.g. Coffee Shop, Park…" />
                            </div>
                            <div class="checkin-field">
                                <label for="note">Note <span class="field-optional">— optional</span></label>
                                <textarea name="note" id="note" class="field-input"
                                          style="height: 72px; resize: none; line-height: 1.5;"
                                          v-model="checkin.note"
                                          placeholder="Any notes about this spot…"></textarea>
                            </div>
                        </div>
                        <div id="select-location" class="tab-pane" role="tabpanel">
                            <div style="font-size: 0.8rem; color: var(--ww-muted); padding: 0.5rem 0;">
                                Click a pin on the map to link this check-in to an existing location.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="checkin-footer">
                <button type="submit" class="btn-submit-checkin">
                    <i class="fa-solid fa-location-dot"></i> Save Location
                </button>
            </div>

        </div>{{-- /checkin-panel --}}

        {{-- Right: map --}}
        <div class="checkin-map-col">
            <checkinmap :dragable="true" />
        </div>

    </div>
</div>

@endsection
