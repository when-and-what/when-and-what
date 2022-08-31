@extends('layouts.bootstrap')

@section('content')
<div class="text-center">
    <h1>{{ $episode->name }}</h1>
</div>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <h2>{{ $play ? 'Edit' : 'Create' }} Play</h2>
            </div>
            <div class="col">
                <form action="{{ route('plays.destroy', $play) }}" method="POST" class="text-end">
                    @method('delete')
                    @csrf
                    <p><input type="submit" class="btn btn-danger" value="Delete" /></p>
                </form>
            </div>
        </div>
        @if($play)
            <form action="{{ route('plays.update', $play) }}" method="POST">
            @method('PUT')
        @else
            <form action="{{ route('episodes.plays.create', $episode) }}" method="POST">
        @endif
            @csrf
            <div class="form-group">
                <label for="seconds">Seconds</label>
                <input type="number" class="form-control @if($errors->has('seconds')) is-invalid @endif" id="seconds" name="seconds" value="{{ old('seconds', optional($play)->seconds) }}" aria-describedby="seconds" />
                @error('seconds')
                    <div id="seconds_feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <p class="pt-3"><input type="submit" class="btn btn-primary" value="Update" /></p>
        </form>
    </div>
</div>
@endsection
