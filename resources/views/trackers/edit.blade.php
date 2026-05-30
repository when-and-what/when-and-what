@extends('layouts.bootstrap')

@section('content')
    @if($tracker)
        <form action="{{ route('trackers.update', $tracker) }}" method="POST">
            @method('PUT')
    @else
        <form action="{{ route('trackers.store') }}" method="POST">
    @endif
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name" name="name" value="{{ old('name', $tracker?->name) }}" aria-describedby="name" />
            @error('name')
                <div id="name_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" class="form-control @if($errors->has('code')) is-invalid @endif" id="code" name="code" value="{{ old('code', $tracker?->code) }}" aria-describedby="code" @if($tracker) disabled @endif />
            @error('code')
                <div id="code_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="unit">Unit</label>
            <select class="form-control @if($errors->has('unit')) is-invalid @endif" id="unit" name="unit" aria-describedby="unit">
                @foreach($units as $type => $uns)
                    <optgroup label="{{ $type }}">
                        @foreach($uns as $unit)
                            <option value="{{ $unit->value }}">{{ $unit->label() }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('unit')
                <div id="unit_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="icon">Icon</label>
            <input type="text" class="form-control @if($errors->has('icon')) is-invalid @endif" id="icon" name="icon" value="{{ old('icon', $tracker?->icon) }}" aria-describedby="icon" />
            @error('icon')
                <div id="icon_feedback" class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <p class="pt-3"><input type="submit" value="{{ $tracker ? 'Update' : 'Create' }}" class="btn btn-primary" /></p>
    </form>
@endsection
