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
<div id="dashboard-container">
    <div class="row pb-3">
        <item v-for="item in items" :item="item" />
    </div>
    <div class="row">
        <div class="col-md-5">
            <ul class="list-group">
                <event v-for="event in sortedEvents" :event="event" />
            </ul>
        </div>
        <div class="col-md-7">
            <div id="dashboard-map" style="height:500px;width100%;">

            </div>
        </div>
    </div>
</div>
<script> var day = "{{ $today->toDateString()}}";</script>
@endsection
