@extends('layouts.bootstrap')

@section('full-content')
<div id="range-container"
     class="dashboard-page"
     data-start="{{ $start->toDateString() }}"
     data-start-time="{{ $startTime ?? '00:00:00' }}"
     data-end="{{ $end->toDateString() }}"
     data-end-time="{{ $endTime ?? '23:59:59' }}"
     data-formatted-start="{{ $start->toFormattedDateString() }}"
     data-formatted-end="{{ $end->toFormattedDateString() }}"
     data-is-memory="{{ isset($memory) ? 'true' : 'false' }}"
     data-title="{{ isset($memory) ? $memory->tags->implode('icon', ' ').' '.$memory->name : '' }}">
</div>
@endsection
