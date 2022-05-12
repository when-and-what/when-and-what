@extends('layouts.bootstrap')
@section('content')
    @if(count($pending) > 0)
        <div class="d-flex w-100 justify-content-between pb-3">
            <h1>Pending</h1>
            {{-- <a href="{{ route('checkins.create') }}" class="btn btn-primary">Check-in</a> --}}
        </div>
        <ul class="list-group list-group-horizontal d-flex w-100">
            @foreach($pending as $checkin)
                <li class="list-group-item w-50">
                    <div class="d-flex w-100 justify-content-between">
                        <h3>
                            <a href="{{ route('pending.edit', $checkin) }}">{{ $checkin->checkin_at->diffForHumans() }}</a>
                        </h3>
                    </div>
                    <p>{{ $checkin->note }}</p>
                </li>
            @endforeach
        </ul>
        <div class="clearfix"></div>
    @endif
    <div class="d-flex w-100 justify-content-between pb-3">
        <h1>Checkins</h1>
        <a href="{{ route('checkins.create') }}" class="btn btn-primary">Check-in</a>
    </div>
    @foreach($checkins as $checkin)
        @if($loop->index % 2 == 0)
        <ul class="list-group list-group-horizontal d-flex w-100">
        @endif
            <li class="list-group-item w-50">
                <div class="d-flex w-100 justify-content-between">
                    <h3>
                        <a href="{{ route('checkins.edit', $checkin) }}">{{ $checkin->location->name }}</a>
                    </h3>
                    <span>{{ $checkin->checkin_at->diffForHumans() }}</span>
                </div>
                <p>{{ $checkin->note }}</p>
            </li>
        @if($loop->index > 0 && $loop->index % 2)
            </ul>
        @endif
    @endforeach
    {{ $checkins->links() }}
@endsection
