@extends('layouts.bootstrap')

@section('content')
<div class="col-12">

    <div class="page-header mb-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <div>
            <h1 class="page-title">Locations</h1>
            <p class="page-subtitle">{{ $locations->total() }} {{ Str::plural('location', $locations->total()) }}</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
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
                <i class="fa-solid fa-plus"></i> New
            </a>
            <a href="{{ route('locations.map') }}" class="page-back-link ms-1">
                <i class="fa-solid fa-map me-1"></i> Map view
            </a>
        </div>
    </div>

    @if($locations->isEmpty())
        <div class="content-card text-center py-5">
            <div style="font-size: 2rem; margin-bottom: 0.75rem;">📍</div>
            <div style="font-weight: 600; color: var(--ww-dark); margin-bottom: 0.25rem;">No locations yet</div>
            <div style="font-size: 0.875rem; color: var(--ww-muted);">Add your first location to start checking in.</div>
        </div>
    @else
        <div class="row g-3">
            @foreach($locations as $location)
            <div class="col-sm-6 col-lg-4">
                <div class="location-list-card">
                    <a href="{{ route('locations.show', $location) }}" class="location-list-card-body">
                        <div class="location-list-card-icon">
                            @if($location->category && $location->category->isNotEmpty())
                                @foreach($location->category as $cat){{ $cat->emoji }}@endforeach
                            @else
                                <i class="fa-solid fa-location-dot" style="color: var(--ww-border); font-size: 1rem;"></i>
                            @endif
                        </div>
                        <div class="location-list-card-name">{{ $location->name }}</div>
                    </a>
                    <div class="location-list-card-footer">
                        <span class="location-list-visits">
                            <i class="fa-solid fa-circle-check me-1" style="color: var(--ww-accent)"></i>
                            {{ $location->checkins_count }} {{ Str::plural('visit', $location->checkins_count) }}
                        </span>
                        <a href="{{ route('locations.edit', $location) }}" class="location-list-edit" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $locations->links() }}
        </div>
    @endif

</div>
@endsection
