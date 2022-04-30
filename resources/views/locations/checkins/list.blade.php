@extends('layouts.bootstrap')
@section('content')
    <div class="d-flex w-100 justify-content-between pb-3">
        <h1>Checkins</h1>
        <a href="{{ route('checkins.create') }}" class="btn btn-primary">Check-in</a>
    </div>
    @foreach($checkins as $count => $checkin)
        @if($count % 2 == 0)
        <ul class="list-group list-group-horizontal d-flex w-100">
        @endif
            <li class="list-group-item w-50">
                <div class="d-flex w-100 justify-content-between">
                    <h3>{{ $checkin->location->name }}</h3>
                    <span>{{ $checkin->checkin_at->diffForHumans() }}</span>
                </div>
                <p>{{ $checkin->note }}</p>
            </li>
        @if($count > 0 && $count % 2)
            </ul>
        @endif
    @endforeach
    {{ $checkins->links() }}
@endsection
