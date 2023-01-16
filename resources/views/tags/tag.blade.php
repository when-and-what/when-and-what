@extends('layouts.bootstrap')

@section('content')
<div class="d-flex">
    <h1>{{ $tag->display_name }}</h1>
    <div class="flex-fill">
        <h3><a href="{{ route('tags.edit', $tag) }}" title="Edit Tag" class="float-end">✏️</a></h3>
    </div>
@endsection
