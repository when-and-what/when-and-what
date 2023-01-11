@extends('layouts.bootstrap')

@section('content')
<div class="d-flex">
    <h1>{{ $tracker->display_name }}</h1>
    <div class="flex-fill">
        <h3><a href="{{ route('trackers.edit', $tracker) }}" title="Edit Tracker" class="float-end">✏️</a></h3>
    </div>
@endsection
