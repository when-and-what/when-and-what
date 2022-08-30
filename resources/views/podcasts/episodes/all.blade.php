@extends('layouts.bootstrap')

@section('content')
    <h1><a href="{{ route('podcasts.edit', $podcast) }}">{{ $podcast->name }}</a></h1>
    <div class="row row-cols-md-4 row-cols-lg-5">
        @foreach($episodes as $episode)
            <div class="col">
                <div class="card">
                    <div class="card-header"><a href="{{ route('episodes.show', $episode) }}">{{ $episode->name }}</a></div>
                    <div class="card-body">
                        {!! strip_tags($episode->description, '<p>') !!}
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            @if($episode->published_at)
                                <span>{{ $episode->published_at->toFormattedDateString() }}</span>
                            @endif
                            <x-seconds seconds="{{ $episode->plays->sum('seconds') }}" />
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $episodes->links() }}
@endsection
