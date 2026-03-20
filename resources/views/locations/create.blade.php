@extends('layouts.bootstrap')

@section('full-content')

<div id="location-container">
    <form action="{{ route('locations.store') }}" method="POST" class="checkin-page">
        @csrf

        {{-- Left panel --}}
        <div class="checkin-panel">

            <div class="checkin-panel-header">
                <h5><i class="fa-solid fa-location-dot me-1" style="color: var(--ww-accent)"></i> New Location</h5>
                <a href="{{ url()->previous() }}" class="checkin-back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="checkin-form-scroll">

                @if ($errors->any())
                    <div class="alert alert-danger mb-0">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="checkin-field">
                    <label for="location-name">Name</label>
                    <input type="text" name="name" id="location-name"
                           value="{{ old('name') }}"
                           placeholder="e.g. Home, Coffee Shop…" />
                </div>

                <div class="checkin-field">
                    <label for="category">Categories <span class="optional">— optional</span></label>
                    <select name="category[]" id="category" class="field-input" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->emoji . ' ' . $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <p class="location-map-hint">
                    <i class="fa-solid fa-hand-pointer"></i>
                    Drag the pin on the map to set the exact location.
                </p>

            </div>

            <div class="checkin-footer">
                <button type="submit" class="btn-submit-checkin">
                    <i class="fa-solid fa-location-dot"></i> Save Location
                </button>
            </div>

        </div>{{-- /checkin-panel --}}

        {{-- Right: map --}}
        <div class="checkin-map-col">
            <newlocation locations draggable latitude="{{ $latitude }}" longitude="{{ $longitude }}" />
        </div>

    </form>
</div>

@endsection
