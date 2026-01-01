@extends('layouts.bootstrap')
@section('content')
<div class="d-flex justify-content-between">
    <h1><a href="{{ route('day', [$yesterday->year, $yesterday->month, $yesterday->day]) }}">⏪️</a></h1>
    <h1 class="text-center">{{ $today->toFormattedDateString() }}</h1>
    <h1><a href="{{ route('day', [$tomorrow->year, $tomorrow->month, $tomorrow->day]) }}">⏭️</a></h1>
</div>
<div class="row">
    @foreach($trackers as $tracker)
        <div class="col d-flex">
            <div class="d-flex align-items-center h-100 pe-3 fs-3">
                {{ $tracker->icon }}
            </div>
            <div class="flex-grow-1">
                {{ $tracker->name }} <br />
                {{  $tracker->events->first()->event_value }}
            </div>
        </div>
    @endforeach
</div>
<div id="dashboard-container">
    <div class="row pb-3">
        <item v-for="item in items" :item="item" />
    </div>
    <div class="row">
        <div class="col-md-5">
            <ul class="list-group">
                <event v-for="event in sortedEvents" :event="event" />
            </ul>
            <div class="accordion accordion-flush" id="add-note-accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="add-note-heading">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#add-note-content" aria-expanded="false" aria-controls="add-note-content">
                        Add Note
                      </button>
                    </h2>
                    <div id="add-note-content" class="accordion-collapse collapse" aria-labelledby="add-note-heading" data-bs-parent="#add-note-accordion">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" aria-describedby="title" v-model="note.title" />
                        </div>
                        <div class="form-group">
                            <label for="sub_title">Sub Title</label>
                            <input type="text" class="form-control" id="sub_title" name="sub_title" aria-describedby="sub_title" v-model="note.sub_title" />
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4">
                                <div class="form-group">
                                    <label for="icon">Icon</label>
                                    <input type="text" class="form-control" id="icon" name="icon" aria-describedby="icon" v-model="note.icon" />
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-8">
                                <div class="form-group">
                                    <label for="published_at">Date</label>
                                    <input type="datetime-local" class="form-control" id="published_at" name="published_at" aria-describedby="published_at" v-model="note.published_at" />
                                </div>
                            </div>
                        </div>
                        <p class="my-3"><button class="btn btn-primary p-2" @click="saveNote">Add Note</button></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div id="dashboard-map" style="height:500px;width100%;"></div>
            <div class="mt-3 mb-5 d-flex justify-content-between">
                <a href="{{ route('pending.create') }}" class="btn btn-primary">📍Save Location</a>

                <a href="{{ route('checkins.create') }}" class="btn btn-primary">➕ New Checkin</a>
            </div>
        </div>
    </div>
</div>
<script> var day = "{{ $today->toDateString()}}";</script>
@endsection
