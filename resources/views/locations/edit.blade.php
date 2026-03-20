@extends('layouts.bootstrap')

@section('full-content')

<div id="location-container">
    <form action="{{ route('locations.update', $location) }}" method="POST" class="checkin-page">
        @method('PUT')
        @csrf

        {{-- Left panel --}}
        <div class="checkin-panel">

            <div class="checkin-panel-header">
                <h5><i class="fa-solid fa-location-dot me-1" style="color: var(--ww-accent)"></i> Edit Location</h5>
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
                           value="{{ old('name', $location->name) }}" />
                </div>

                <div class="checkin-field">
                    <label for="category">Categories <span class="optional">— optional</span></label>
                    <select name="category[]" id="category" class="field-input" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @if(in_array($category->id, $locationCategories)) selected @endif>
                                {{ $category->emoji . ' ' . $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <p class="location-map-hint">
                    <i class="fa-solid fa-hand-pointer"></i>
                    Drag the pin to adjust the pin location.
                </p>

                <button type="button" class="checkin-delete-link"
                        onclick="if(confirm('Delete this location and all its check-ins?')) document.getElementById('location-delete-form').submit()">
                    <i class="fa-solid fa-trash"></i> Delete location
                </button>

            </div>

            <div class="checkin-footer">
                <button type="submit" class="btn-submit-checkin">
                    <i class="fa-solid fa-floppy-disk"></i> Update Location
                </button>
            </div>

        </div>{{-- /checkin-panel --}}

        {{-- Right: map --}}
        <div class="checkin-map-col">
            <newLocation draggable
                         latitude="{{ $location->latitude }}"
                         longitude="{{ $location->longitude }}" />
        </div>

    </form>
</div>

{{-- Delete form lives outside the update form --}}
<form id="location-delete-form"
      action="{{ route('locations.destroy', $location) }}"
      method="POST">
    @csrf
    @method('delete')
</form>

@endsection
