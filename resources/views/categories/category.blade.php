@extends('layouts.bootstrap')

@section('content')

{{-- Page header --}}
<div class="col-12">
    <div class="location-show-header">

        <div class="location-show-title-row">
            <div class="location-show-title-group">
                @if($category->emoji)
                    <span class="location-show-categories">{{ $category->emoji }}</span>
                @endif
                <h1 class="location-show-name">{{ $category->name }}</h1>
            </div>
            <div class="location-show-actions">
                <a href="{{ route('categories.edit', $category) }}" class="btn-location-action btn-location-secondary">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
            </div>
        </div>

        <div class="location-show-meta">
            <span class="location-show-stat">
                <i class="fa-solid fa-map-pin"></i>
                {{ $locations->total() }} {{ Str::plural('location', $locations->total()) }}
            </span>
        </div>

    </div>
</div>

{{-- Locations list --}}
<div class="col-12">
    <div class="location-checkins-card">

        <div class="location-checkins-card-header">
            <span class="location-checkins-card-title">Locations</span>
            @if($locations->total() > $locations->perPage())
                <span class="location-checkins-showing">
                    Showing {{ $locations->firstItem() }}–{{ $locations->lastItem() }} of {{ $locations->total() }}
                </span>
            @endif
        </div>

        @if($locations->isEmpty())
            <div class="location-checkins-empty">
                <i class="fa-solid fa-map-pin"></i>
                <p>No locations in this category yet.</p>
            </div>
        @else
            <div class="location-checkin-list">
                @foreach($locations as $location)
                    <a href="{{ route('locations.show', $location) }}" class="location-checkin-item">
                        <div class="location-checkin-dot"></div>
                        <div class="location-checkin-body">
                            <span class="location-checkin-time">{{ $location->name }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-right location-checkin-arrow"></i>
                    </a>
                @endforeach
            </div>

            <div class="p-3">
                {{ $locations->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
