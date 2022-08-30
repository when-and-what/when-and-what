@extends('layouts.bootstrap')

@section('content')
    <div class="row row-cols-md-3 row-cols-lg-4">
        @foreach($recent as $play)
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('episodes.show', $play->episode) }}">{{ $play->episode->name }}</a>
                    </div>
                    <div class="card-body">
                        @if($play->episode->podcast->image)
                            <a href="{{ route('podcasts.episodes.index', $play->episode->podcast) }}">
                                <img src="{{ $play->episode->podcast->image }}" class="img-fluid" alt="{{ $play->episode->podcast->nickname }}" />
                            </a>
                        @else
                            <a href="{{ route('podcasts.episodes.index', $play->episode->podcast) }}">{{ $play->episode->podcast->nickname }}</a>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <span>{{ $play->last_played_at->tz('America/Chicago')->toDayDateTimeString() }}</span>
                            <x-seconds seconds="{{ $play->seconds }}" />
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $recent->links() }}
@endsection
