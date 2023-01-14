@extends('layouts.bootstrap')

@section('content')
<div class="d-flex">
    <div><h1>Trackers</h1></div>
    <div class="flex-fill">
        <h3><a href="{{ route('trackers.create') }}" title="New Tracker" class="float-end">➕️</a></h3>
    </div>
</div>
<div class="row">
    @foreach($trackers as $tracker)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('trackers.edit', $tracker) }}">{{ $tracker->name }}</a>
        </div>
    @endforeach
</div>
@endsection
