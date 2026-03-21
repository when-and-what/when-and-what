@extends('layouts.bootstrap')

@section('content')
<div class="col-lg-8 col-xl-6">

    <div class="page-header mb-4">
        <a href="{{ route('categories.index') }}" class="page-back-link mb-2">
            <i class="fa-solid fa-arrow-left"></i> Categories
        </a>
        <h1 class="page-title">{{ $category ? 'Edit Category' : 'New Category' }}</h1>
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

    @if($category)
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @method('PUT')
    @else
    <form action="{{ route('categories.store') }}" method="POST">
    @endif
        @csrf

        <div class="content-card mb-3">

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <div class="field-group mb-0">
                        <label class="field-label" for="name">Name</label>
                        <input type="text"
                               class="field-input{{ $errors->has('name') ? ' is-invalid' : '' }}"
                               id="name" name="name"
                               value="{{ old('name', $category?->name) }}"
                               autofocus />
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-group mb-0">
                        <label class="field-label" for="emoji">Emoji <span class="field-optional">— optional</span></label>
                        <input type="text"
                               class="field-input{{ $errors->has('emoji') ? ' is-invalid' : '' }}"
                               id="emoji" name="emoji"
                               value="{{ old('emoji', $category?->emoji) }}"
                               placeholder="e.g. 🍕" />
                        @error('emoji')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            @if($category)
            <div class="mt-4 pt-3" style="border-top: 1px solid var(--ww-border);">
                <a href="#"
                   class="delete-link"
                   onclick="event.preventDefault(); if(confirm('Delete this category?')) document.getElementById('delete-category-form').submit();">
                    <i class="fa-solid fa-trash-can me-1"></i> Delete this category
                </a>
            </div>
            @endif

        </div>

        <button type="submit" class="btn-submit-checkin" style="width: auto; padding: 0.65rem 2rem;">
            <i class="fa-solid fa-{{ $category ? 'floppy-disk' : 'plus' }}"></i>
            {{ $category ? 'Update Category' : 'Create Category' }}
        </button>

    </form>

    @if($category)
    <form id="delete-category-form" action="{{ route('categories.destroy', $category) }}" method="POST" class="d-none">
        @method('DELETE')
        @csrf
    </form>
    @endif

</div>
@endsection
