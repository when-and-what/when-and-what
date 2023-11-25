@extends('layouts.bootstrap')

@section('content')
<div class="d-flex">
    <div><h1>Notes</h1></div>
    <div class="flex-fill">
        <h3><a href="{{ route('notes.create') }}" title="New Note" class="float-end">➕️</a></h3>
    </div>
</div>
<div class="list-group">
    @foreach($notes as $note)
        <div class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h4><a href="{{ route('notes.show', $note) }}">{{ $note->title }}</a></h4>
                <a href="{{ route('notes.edit', $note) }}"><small class="text-body-secondary">{{ $note->published_at->diffForHumans() }}</small></a>
            </div>
            @if($note->sub_title) <p><small class="text-body-secondary">{{ $note->sub_title }}</small></p> @endif
            {{ $note->note }}
        </div>
    @endforeach
</div>
@endsection
