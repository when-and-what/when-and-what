@extends('layouts.bootstrap')
@section('content')
    <div class="d-flex w-100 justify-content-between pb-3">
        <h1>Locations</h1>
        <a href="{{ route('locations.create') }}" class="btn btn-primary">New Location</a>
    </div>
    @foreach($locations as $count => $location)
        @if($count % 2 == 0)
        <ul class="list-group list-group-horizontal d-flex w-100">
        @endif
            <li class="list-group-item w-50">
                <h3>
                    @if($location->category)
                        @foreach ($location->category as $category)
                            {{ $category->emoji }}
                        @endforeach
                    @endif
                    <a href="{{ route('locations.show', $location) }}">{{ $location->name }}</a>
                </h3>
            </li>
        @if($count > 0 && $count % 2)
            </ul>
        @endif
    @endforeach
    {{ $locations->links() }}
@endsection
