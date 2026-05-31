@extends('layouts.bootstrap')

@section('content')
<div class="col-lg-8 col-xl-6">

    <div class="page-header mb-4">
        <a href="{{ route('memories.index') }}" class="page-back-link mb-2">
            <i class="fa-solid fa-arrow-left"></i> Memories
        </a>
        <h1 class="page-title">New Memory</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('memories.store') }}" method="POST">
        @csrf

        <div class="content-card mb-3">

            <div class="field-group mb-3">
                <label class="field-label" for="name">Name</label>
                <input type="text"
                       class="field-input{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       id="name" name="name"
                       value="{{ old('name') }}"
                       placeholder="e.g. Rome Trip 2026" />
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="field-group mb-0">
                        <label class="field-label" for="start_date">Start Date</label>
                        <input type="date"
                               class="field-input{{ $errors->has('start_date') ? ' is-invalid' : '' }}"
                               id="start_date" name="start_date"
                               value="{{ old('start_date') }}" />
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-group mb-0">
                        <label class="field-label" for="start_time">Start Time <span class="field-optional">— optional</span></label>
                        <input type="time"
                               class="field-input{{ $errors->has('start_time') ? ' is-invalid' : '' }}"
                               id="start_time" name="start_time"
                               value="{{ old('start_time') }}" />
                        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="field-group mb-0">
                        <label class="field-label" for="end_date">End Date</label>
                        <input type="date"
                               class="field-input{{ $errors->has('end_date') ? ' is-invalid' : '' }}"
                               id="end_date" name="end_date"
                               value="{{ old('end_date') }}" />
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-group mb-0">
                        <label class="field-label" for="end_time">End Time <span class="field-optional">— optional</span></label>
                        <input type="time"
                               class="field-input{{ $errors->has('end_time') ? ' is-invalid' : '' }}"
                               id="end_time" name="end_time"
                               value="{{ old('end_time') }}" />
                        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            @if($tags->isNotEmpty())
            <div class="field-group mb-0">
                <label class="field-label">Tags <span class="field-optional">— optional</span></label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                    <label class="d-flex align-items-center gap-1" style="cursor: pointer;">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                               @checked(in_array($tag->id, old('tags', []))) />
                        <span>{{ $tag->icon }} {{ $tag->display_name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <button type="submit" class="btn-submit-checkin" style="width: auto; padding: 0.65rem 2rem;">
            <i class="fa-solid fa-plus"></i> Create Memory
        </button>

    </form>

</div>
@endsection
