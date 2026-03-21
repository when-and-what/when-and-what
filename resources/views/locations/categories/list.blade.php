@extends('layouts.bootstrap')

@section('content')
<div class="col-12">

    <div class="page-header mb-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <div>
            <h1 class="page-title">Categories</h1>
            <p class="page-subtitle">{{ $categories->count() }} {{ Str::plural('category', $categories->count()) }}</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn-submit-checkin text-decoration-none" style="width: auto; padding: 0.45rem 1rem; font-size: 0.85rem;">
            <i class="fa-solid fa-plus"></i> New
        </a>
    </div>

    @if($categories->isEmpty())
        <div class="content-card text-center py-5">
            <div style="font-size: 2rem; margin-bottom: 0.75rem;">🗂️</div>
            <div style="font-weight: 600; color: var(--ww-dark); margin-bottom: 0.25rem;">No categories yet</div>
            <div style="font-size: 0.875rem; color: var(--ww-muted);">Create a category to organize your locations.</div>
        </div>
    @else
        <div class="row g-3">
            @foreach($categories as $category)
            <div class="col-sm-6 col-lg-4">
                <div class="location-list-card">
                    <a href="{{ route('categories.show', $category) }}" class="location-list-card-body">
                        <div class="location-list-card-icon">
                            {{ $category->emoji ?: '🗂️' }}
                        </div>
                        <div class="location-list-card-name">{{ $category->name }}</div>
                    </a>
                    <div class="location-list-card-footer">
                        <span class="location-list-visits">
                            <i class="fa-solid fa-map-pin me-1" style="color: var(--ww-accent)"></i>
                            {{ $category->locations_count }} {{ Str::plural('location', $category->locations_count) }}
                        </span>
                        <a href="{{ route('categories.edit', $category) }}" class="location-list-edit" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
