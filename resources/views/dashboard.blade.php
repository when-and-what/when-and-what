@extends('layouts.bootstrap')
@section('content')
<div class="d-flex justify-content-between">
    <h1><a href="{{ route('day', [$yesterday->year, $yesterday->month, $yesterday->day]) }}">⏪️</a></h1>
    <h1 class="text-center">{{ $today->toFormattedDateString() }}</h1>
    <h1><a href="{{ route('day', [$tomorrow->year, $tomorrow->month, $tomorrow->day]) }}">⏭️</a></h1>
</div>
<ul class="list-group list-group-horizontal d-flex w-100">
    @foreach($checkins as $checkin)
        <li class="list-group-item w-50">
            <div class="d-flex w-100 justify-content-between">
                <h3>
                    @foreach($checkin->location->category as $category)
                        @if($category->emoji)
                            <span title="{{ $category->name }}">{{ $category->emoji }}</span>
                        @endif
                    @endforeach
                    <a href="{{ route('locations.show', $checkin->location) }}">{{ $checkin->location->name }}</a>
                </h3>
                <a href="{{ route('checkins.edit', $checkin) }}">{{ $checkin->checkin_at->tz(Auth::user()->timezone)->format('g:i a') }}</a>
            </div>
            <p>{{ $checkin->note }}</p>
        </li>
    @endforeach
</ul>
<div class="d-flex justify-content-between">
    <div>
        <h2><a href="{{ route('checkins.index') }}">Checkins</a></h2>
        <a href="{{ route('checkins.create') }}" class="btn btn-primary">Check-in</a>
    </div>
    <div>
        <h2><a href="{{ route('locations.index') }}">Locations</a></h2>
        <a href="{{ route('locations.create') }}" class="btn btn-primary">New Location</a>
    </div>
    <div>
        <h2><a href="{{ route('categories.index') }}">Categories</a></h2>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">New Category</a>
</div>
@endsection
