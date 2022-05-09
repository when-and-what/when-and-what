@extends('layouts.bootstrap')
@section('content')
    <h1>Categories <a href="{{ route('categories.create') }}" class="btn">➕️</a></h1>
    <div class="row gx-5 gy-2">

    {{-- <ul class="list-group list-group-horizontal"> --}}
        @foreach($categories as $category)
            {{-- <li class="list-group-item flex-fill d-flex justify-content-between align-items-center"> --}}
            <div class="col border col-lg-3 col-md-4 p-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('categories.edit', $category) }}">{{ $category->emoji.' '.$category->name }}</a>
                <span class="badge bg-primary rounded-pill">{{ $category->locations_count }}</span>
            </div>
        @endforeach
    </div>
    {{-- </ul> --}}
@endsection
