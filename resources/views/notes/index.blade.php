@extends('layouts.bootstrap')

@section('content')
<div class="d-flex">
    <div><h1>Notes</h1></div>
    <div class="flex-fill">
        <h3><a href="{{ route('notes.create') }}" title="New Note" class="float-end">➕️</a></h3>
    </div>
</div>
<div class="row">
    @foreach($notes as $note)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('notes.edit', $note) }}">{{ $note->title }}</a>
        </div>
    @endforeach
</div>
@endsection
