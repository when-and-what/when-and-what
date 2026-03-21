@extends('layouts.bootstrap')

@section('full-content')
<div id="location-container" class="locations-map-page">

    <div class="locations-map-toolbar">
        <div class="locations-map-toolbar-left">
            <span class="locations-map-title">
                <i class="fa-solid fa-map me-2" style="color: var(--ww-accent)"></i>Locations
            </span>
        </div>
        <div class="locations-map-toolbar-right">
            <form action="{{ route('locations.search') }}" method="POST" class="locations-map-search">
                @csrf
                <div class="locations-search-wrap">
                    <i class="fa-solid fa-magnifying-glass locations-search-icon"></i>
                    <input type="search" name="search" class="locations-search-input"
                           value="{{ old('search') }}" placeholder="Search locations…" />
                </div>
                <button type="submit" class="btn-submit-checkin" style="width: auto; padding: 0.45rem 1rem; font-size: 0.85rem;">
                    Search
                </button>
            </form>
            <a href="{{ route('locations.create') }}" class="btn-submit-checkin text-decoration-none" style="width: auto; padding: 0.45rem 1rem; font-size: 0.85rem;">
                <i class="fa-solid fa-plus"></i> New Location
            </a>
        </div>
    </div>

    <locationsmap />

</div>
@endsection
