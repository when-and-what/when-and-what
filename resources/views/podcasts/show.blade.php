@extends('layouts.bootstrap')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <h1><a href="{{ route('podcasts.edit', $podcast) }}">{{ $podcast->name }}</a></h1>
        </div>
        <div class="col-md-8">
            <h2>Episodes</h2>
            <div class="row row-cols-md-2 row-cols-lg-3">
                @foreach($episodes as $episode)
                    <div class="col">
                        <h3><a href="{{ route('episodes.show', $episode) }}">{{ $episode->name }}</a></h3>
                        @if( !$episode->imported)
                            <p>❔️</p>
                        @endif
                        @if($episode->published_at)
                            {{ $episode->published_at->toFormattedDateString() }}
                        @endif
                        @if($episode->duration)
                            {{-- <span class="badge bg-secondary">{{seconds_to_human($episode->duration) }}</span> --}}
                            <span class="badge bg-secondary">{{ $episode->duration }}</span>
                        @endif
                        @if($episode->description)
                            <p>{{ $episode->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
