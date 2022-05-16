@extends('layouts.bootstrap')
@section('content')
    <h1>{{ $location->name }}</h1>
    <h2>Checkins</h2>
    <ul class="list-group">
        @foreach($checkins as $checkin)
            <a href="{{ route('checkins.edit', $checkin) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">List group item heading</h5>
                    <small class="text-muted">3 days ago</small>
                </div>
                @if($checkin->note)
                <p class="mb-1"> {{ $checkin->note }}</p>
                @endif
            </a>
        @endforeach
    </ul>
@endsection
