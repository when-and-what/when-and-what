@extends('layouts.bootstrap')

@section('content')
<div class="text-center">
    <h1>{{ $episode->name }}</h1>
</div>
<div class="row">
    <div class="col">
        <h2>Edit</h2>
        <p class="pt-3"><input type="submit" class="btn btn-primary" value="Update" /></p>
    </div>
</div>
@endsection
