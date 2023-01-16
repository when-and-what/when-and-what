@extends('layouts.bootstrap')

@section('content')
<div class="d-flex">
    <div><h1>Tags</h1></div>
    <div class="flex-fill">
        <h3><a href="{{ route('tags.create') }}" title="New Tag" class="float-end">➕️</a></h3>
    </div>
</div>
<div class="row">
    @foreach($tags as $tag)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('tags.edit', $tag) }}">{{ $tag->name }}</a>
        </div>
    @endforeach
</div>
@endsection
