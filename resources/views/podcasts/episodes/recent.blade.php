@extends('layouts.bootstrap')

@section('content')
    <div class="row row-cols-md-3 row-cols-lg-4">
        @foreach($recent as $play)
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        {{ $play->episode->title }}
                    </div>
                    <div class="card-body">
                        @if($play->episode->podcast->image)
                            <img src="{{ $play->episode->podcast->image }}" class="img-fluid" alt="{{ $play->episode->podcast->title }}" />
                        @else
                            {{ $play->episode->podcast->title }}
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
