@extends('layouts.bootstrap')

@section('full-content')
<div id="dashboard-container"
     class="dashboard-page"
     data-day="{{ $today->toDateString() }}"
     data-formatted-date="{{ $today->toFormattedDateString() }}"
     data-yesterday-url="{{ route('day', [$yesterday->year, $yesterday->month, $yesterday->day]) }}"
     data-tomorrow-url="{{ route('day', [$tomorrow->year, $tomorrow->month, $tomorrow->day]) }}">
</div>
@endsection
