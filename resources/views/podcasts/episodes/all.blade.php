@extends('layouts.bootstrap')

@section('content')
    <h1><a href="{{ route('podcasts.edit', $podcast) }}">{{ $podcast->name }}</a></h1>
    <div class="row row-cols-md-4 row-cols-lg-5">
        @foreach($episodes as $episode)
            <div class="col">
                <div class="card">
                    <div class="card-header">{{ $episode->name }}</div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $episodes->links() }}
@endsection
