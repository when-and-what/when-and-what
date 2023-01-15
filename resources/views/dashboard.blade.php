@extends('layouts.bootstrap')
@section('content')
<div class="d-flex justify-content-between">
    <h1><a href="{{ route('day', [$yesterday->year, $yesterday->month, $yesterday->day]) }}">⏪️</a></h1>
    <h1 class="text-center">{{ $today->toFormattedDateString() }}</h1>
    <h1><a href="{{ route('day', [$tomorrow->year, $tomorrow->month, $tomorrow->day]) }}">⏭️</a></h1>
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
            <fieldset class="pt-3">
                <legend>Add Note</legend>
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
                <p><button class="btn btn-primary" @click="saveNote">Add</button></p>
            </fieldset>
        </div>
        <div class="col-md-7">
            <div id="dashboard-map" style="height:500px;width100%;">

            </div>
        </div>
    </div>
</div>
<script> var day = "{{ $today->toDateString()}}";</script>
@endsection
