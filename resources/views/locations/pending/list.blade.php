@extends('layouts.bootstrap')
@section('content')
    <div class="d-flex w-100 justify-content-between pb-3">
        <h1>Pending Checkins</h1>
    </div>
    @foreach($checkins as $count => $checkin)
        @if($count % 2 == 0)
        <ul class="list-group list-group-horizontal d-flex w-100">
        @endif
            <li class="list-group-item w-50">
                @if($checkin->name)
                    <div class="d-flex w-100 justify-content-between">
                        <h3><a href="{{ route('pending.edit', $checkin) }}">{{ $checkin->name }}</a></h3>
                        <span>{{ $checkin->checkin_at->diffForHumans() }}</span>
                    </div>
                @else
                    <h3><a href="{{ route('pending.edit', $checkin) }}">{{ $checkin->checkin_at->toDayDateTimeString() }}</a></h3>
                @endif
                <p>{{ $checkin->note }}</p>
            </li>
        @if($count > 0 && $count % 2)
            </ul>
        @endif
    @endforeach
    {{ $checkins->links() }}
@endsection
