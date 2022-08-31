@extends('layouts.bootstrap')

@section('content')
<div class="text-center">
    <h1><a href="{{ route('episodes.edit', $episode) }}">{{ $episode->name }}</a></h1>
    <h2><a href="{{ route('podcasts.show', $episode->podcast) }}">{{ $episode->podcast->name }}</a></h2>
</div>
<div class="row">
    <div class="col">
        <h2>Plays</h2>
        @foreach($plays as $play)
            <p>
                {{ $play->played_at->tz('America/Chicago')->toDayDateTimeString() }}
                <a href="{{ route('plays.edit', $play) }}"><x-seconds seconds="{{ $play->seconds }}" /></a>
            </p>
        @endforeach
    </div>
    <div class="col">
        <h2>Rating</h2>
        <form action="{{ route('episodes.rating.update', $episode) }}" method="post">
            @csrf
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" class="form-control @if($errors->has('rating')) is-invalid @endif" id="rating" name="rating" value="{{ old('rating', optional($rating)->rating) }}" aria-describedby="rating" min="1" max="5" />
                @error('rating')
                    <div id="rating_feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control @if($errors->has('notes')) is-invalid @endif" id="notes" name="notes" aria-describedby="notes">{{ old('notes', optional($rating)->notes) }}</textarea>
                @error('notes')
                    <div id="notes_feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <p class="pt-3"><input type="submit" class="btn btn-primary" value="Update" /></p>
        </form>
    </div>
</div>
@endsection
