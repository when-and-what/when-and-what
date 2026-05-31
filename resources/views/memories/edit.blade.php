@extends('layouts.bootstrap')

@section('content')
<div class="col-lg-8 col-xl-6">

    <div class="page-header mb-4">
        <a href="{{ route('memories.show', $memory) }}" class="page-back-link mb-2">
            <i class="fa-solid fa-arrow-left"></i> {{ $memory->name }}
        </a>
        <h1 class="page-title">Edit Memory</h1>
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

    <form action="{{ route('memories.update', $memory) }}" method="POST">
        @method('PUT')
        @csrf

        <div class="content-card mb-3">

            <div class="field-group mb-3">
                <label class="field-label" for="name">Name</label>
                <input type="text"
                       class="field-input{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       id="name" name="name"
                       value="{{ old('name', $memory->name) }}" />
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="field-group mb-0">
                        <label class="field-label" for="start_date">Start Date</label>
                        <input type="date"
                               class="field-input{{ $errors->has('start_date') ? ' is-invalid' : '' }}"
                               id="start_date" name="start_date"
                               value="{{ old('start_date', $memory->start_date->toDateString()) }}" />
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-group mb-0">
                        <label class="field-label" for="start_time">Start Time <span class="field-optional">— optional</span></label>
                        <input type="time"
                               class="field-input{{ $errors->has('start_time') ? ' is-invalid' : '' }}"
                               id="start_time" name="start_time"
                               value="{{ old('start_time', $memory->start_time) }}" />
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
                               value="{{ old('end_date', $memory->end_date->toDateString()) }}" />
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-group mb-0">
                        <label class="field-label" for="end_time">End Time <span class="field-optional">— optional</span></label>
                        <input type="time"
                               class="field-input{{ $errors->has('end_time') ? ' is-invalid' : '' }}"
                               id="end_time" name="end_time"
                               value="{{ old('end_time', $memory->end_time) }}" />
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
                               @checked(in_array($tag->id, old('tags', $memory->tags->pluck('id')->toArray()))) />
                        <span>{{ $tag->icon }} {{ $tag->display_name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mt-4 pt-3" style="border-top: 1px solid var(--ww-border);">
                <a href="#"
                   class="delete-link"
                   onclick="event.preventDefault(); if(confirm('Delete this memory?')) document.getElementById('delete-memory-form').submit();">
                    <i class="fa-solid fa-trash-can me-1"></i> Delete this memory
                </a>
            </div>

        </div>

        <button type="submit" class="btn-submit-checkin" style="width: auto; padding: 0.65rem 2rem;">
            <i class="fa-solid fa-floppy-disk"></i> Update Memory
        </button>

    </form>

    <form id="delete-memory-form" action="{{ route('memories.destroy', $memory) }}" method="POST" class="d-none">
        @method('DELETE')
        @csrf
    </form>

</div>
@endsection
