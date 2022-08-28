@extends('layouts.bootstrap')

@section('content')
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            @if($podcast)
                <form action="{{ route('podcasts.update', $podcast) }}" method="POST">
                    @method('PUT')
            @else
                <form action="{{ route('podcasts.store') }}" method="POST">
            @endif
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" value="{{ old('name', optional($podcast)->name) }}" aria-describedby="name" required />
                    @error('name')
                        <div id="name_feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nickname">Nickname</label>
                    <input type="text" class="form-control @if($errors->has('nickname')) is-invalid @endif" id="nickname" name="nickname" value="{{ old('nickname', optional($podcast)->nickname) }}" aria-describedby="nickname" />
                    @error('nickname')
                        <div id="nickname_feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="url" class="form-control @if($errors->has('image')) is-invalid @endif" id="image" name="image" value="{{ old('image', optional($podcast)->image) }}" aria-describedby="image" />
                    @error('image')
                        <div id="image_feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="website">Website</label>
                    <input type="url" class="form-control @if($errors->has('website')) is-invalid @endif" id="website" name="website" value="{{ old('website', optional($podcast)->website) }}" aria-describedby="website" />
                    @error('website')
                        <div id="website_feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="feed">RSS Feed</label>
                    <input type="url" class="form-control @if($errors->has('feed')) is-invalid @endif" id="feed" name="feed" value="{{ old('feed', optional($podcast)->feed) }}" aria-describedby="feed" />
                    @error('feed')
                        <div id="feed_feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <p><input type="submit" name="submit" value="{{ $podcast ? 'Update' : 'Create' }}" class="btn btn-primary mt-2" /></p>
            </form>
        </div>
    </div>
@endsection
