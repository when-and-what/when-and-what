@extends('layouts.bootstrap')
@section('content')
    <h1>New Location</h1>

    <div id="location-container" class="row">
        <div class="col">
            <checkinmap locations draggable />
        </div>
        <div class="col">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item">
                    <a href="#" class="nav-link active" data-bs-toggle="tab" data-bs-target="#new-location" role="tab" aria-controls="new-location" aria-selected="true">New Location</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#select-location" role="tab" aria-controls="select-location">Select Location</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="new-location" class="tab-pane show active" role="tabpanel">
                    <div>
                        <label for="location-name">Name</label>
                        <input type="text" name="name" id="location-name" value="" class="form-control" />
                    </div>
                    <div>
                        <label for="note">Note</label>
                        <textarea name="note" id="note" v-model="checkin.note"></textarea>
                    </div>
                </div>
                <div id="select-location" class="tab-pane" role="tabpanel">

                </div>
            </div>
        </div>
    </div>
@endsection
{{--
<x-app-layout>
    <x-slot name="header">
        @if($checkin->name)
            {{ $checkin->name }}
        @else
            {{ $checkin->checkin_at->toDayDateTimeString() }}
        @endif
    </x-slot>

    <div id="pending-checkin" class="py-12">
        <checkinmap />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-label for="note" value="Note" />
                <textarea name="note" id="note" v-model="checkin.note" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"></textarea>
            </div>
        </div>
    </div>
</x-app-layout> --}}
