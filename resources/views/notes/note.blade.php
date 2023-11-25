@extends('layouts.bootstrap')

@section('content')
    <h2>{{ $note->title }}</h2>
    <small>{{ $note->sub_title }}</small>
    {{ $note->note }}
@endsection
