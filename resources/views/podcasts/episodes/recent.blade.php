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
                        <a href="{{ route('podcasts.episodes.index', $play->episode->podcast) }}">{{ $play->episode->podcast->nickname }}</a>
                        @if($play->episode->podcast->image)
                            <img src="{{ $play->episode->podcast->image }}" class="img-fluid" alt="{{ $play->episode->podcast->name }}" />
                        @endif
                    </div>
                    <div class="card-footer">
                        {{-- <span class="badge bg-primary">{{ seconds_to_human($play->seconds) }}</span>  --}}
                        <span class="badge bg-primary">{{ $play->seconds }}</span>
                        {{ $play->last_played_at->tz('America/Chicago')->toDayDateTimeString() }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $recent->links() }}
@endsection
