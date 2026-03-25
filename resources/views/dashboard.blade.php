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
            <div class="day-empty-state" v-show="events.length === 0">
                <i class="fa-solid fa-calendar"></i>
                <p>No activities recorded<br>for this day.</p>
            </div>

            {{-- Feed --}}
            <div class="day-feed-scroll" v-show="events.length > 0">
                <component
                    :is="item.isGroup ? 'event-group' : 'event'"
                    v-for="item in groupedFeed"
                    :key="item.isGroup ? item.color : item.id"
                    :group="item"
                    :event="item"
                />
            </div>

            {{-- Add Note form --}}
            <div class="note-form-panel" v-show="showNoteForm">
                <div class="note-form-header">
                    <span class="note-form-title">
                        <i class="fa-solid fa-note-sticky me-1" style="color: var(--ww-accent)"></i> Add a Note
                    </span>
                    <button class="note-form-close" @click="showNoteForm = false" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="note-form-body">
                    <div class="checkin-field">
                        <label>Title</label>
                        <input type="text" v-model="note.title" placeholder="What happened?" />
                    </div>
                    <div class="checkin-field">
                        <label>Details <span class="optional">— optional</span></label>
                        <input type="text" v-model="note.sub_title" placeholder="More detail…" />
                    </div>
                    <div class="note-form-row">
                        <div class="checkin-field">
                            <label>Icon <span class="optional">— optional</span></label>
                            <input type="text" v-model="note.icon" placeholder="⭐" />
                        </div>
                        <div class="checkin-field">
                            <label>Time <span class="optional">— optional</span></label>
                            <input type="datetime-local" v-model="note.published_at" />
                        </div>
                    </div>
                    <button class="btn-checkin btn-checkin-primary" @click="saveNote" type="button">
                        <i class="fa-solid fa-floppy-disk"></i> Save Note
                    </button>
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
                <button class="btn-checkin btn-checkin-secondary" :class="{ 'btn-checkin-active': showNoteForm }" @click="showNoteForm = !showNoteForm" type="button">
                    <i class="fa-solid fa-note-sticky"></i> Add Note
                </button>
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
