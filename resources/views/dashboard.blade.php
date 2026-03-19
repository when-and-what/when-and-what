@extends('layouts.bootstrap')

@section('full-content')
<div id="dashboard-container" class="dashboard-page">

    {{-- Stats bar --}}
    <div class="day-stats-bar">
        <item v-for="item in items" :item="item" :key="item.name" />
    </div>

    {{-- Two-column layout --}}
    <div class="day-layout">

        {{-- Left: activity feed --}}
        <div class="day-feed">

            {{-- Day navigation header --}}
            <div class="day-feed-header">
                <a class="day-nav-btn" href="{{ route('day', [$yesterday->year, $yesterday->month, $yesterday->day]) }}" title="Previous day">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <div class="day-feed-header-text">
                    <h5 @click="changeDate">
                        <span v-if="changeDay">
                            <input type="date" class="day-date-input" v-model="date" v-on:change="redirectDate" />
                        </span>
                        <span v-else>{{ $today->toFormattedDateString() }}</span>
                    </h5>
                </div>
                <a class="day-nav-btn" href="{{ route('day', [$tomorrow->year, $tomorrow->month, $tomorrow->day]) }}" title="Next day">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>

            {{-- Empty state --}}
            <div class="day-empty-state" v-show="sortedEvents.length === 0">
                <i class="fa-solid fa-calendar"></i>
                <p>No activities recorded<br>for this day.</p>
            </div>

            {{-- Feed --}}
            <div class="day-feed-scroll" v-show="sortedEvents.length > 0">
                <event v-for="event in sortedEvents" :event="event" :key="event.id" />

                {{-- Add Note --}}
                <div class="add-note-accordion accordion accordion-flush" id="add-note-accordion">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header" id="add-note-heading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#add-note-content" aria-expanded="false" aria-controls="add-note-content">
                                + Add Note
                            </button>
                        </h2>
                        <div id="add-note-content" class="accordion-collapse collapse" aria-labelledby="add-note-heading">
                            <div class="accordion-body">
                                <div class="row g-2 mb-2">
                                    <div class="col-12">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control form-control-sm" v-model="note.title" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Sub Title</label>
                                        <input type="text" class="form-control form-control-sm" v-model="note.sub_title" />
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label">Icon</label>
                                        <input type="text" class="form-control form-control-sm" v-model="note.icon" />
                                    </div>
                                    <div class="col-9">
                                        <label class="form-label">Date</label>
                                        <input type="datetime-local" class="form-control form-control-sm" v-model="note.published_at" />
                                    </div>
                                </div>
                                <button class="btn-checkin btn-checkin-primary" @click="saveNote">Save Note</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Check-in action bar --}}
            <div class="checkin-bar">
                <a href="{{ route('checkins.create') }}" class="btn-checkin btn-checkin-primary">
                    <i class="fa-solid fa-location-dot"></i> Check In
                </a>
                <a href="{{ route('pending.create') }}" class="btn-checkin btn-checkin-secondary">
                    <i class="fa-solid fa-crosshairs"></i> Drop a Pin
                </a>
            </div>

        </div>{{-- /day-feed --}}

        {{-- Right: map --}}
        <div class="day-map-col">
            <div id="dashboard-map"></div>
        </div>

    </div>{{-- /day-layout --}}

</div>{{-- /dashboard-container --}}

<script>var day = "{{ $today->toDateString() }}";</script>
@endsection
