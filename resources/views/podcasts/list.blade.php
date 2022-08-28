@extends('layouts.bootstrap')

@section('content')
    <div class="row row-cols-md-3 row-cols-lg-4">
        @foreach($podcasts as $podcast)
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('podcasts.episodes.index', $podcast) }}">{{ $podcast->nickname }}</a>
                    </div>
                    <div class="card-body">
                        @if($podcast->image)
                            <img src="{{ $podcast->image }}" class="img-fluid" alt="{{ $podcast->name }}" />
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $podcasts->links() }}
@endsection
