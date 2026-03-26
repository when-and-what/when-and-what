<template>
    <div class="day-stats-bar">
        <item v-for="stat in items" :item="stat" :key="stat.name" />
    </div>

    <div class="day-layout">

        <div class="day-feed">

            <div class="day-feed-header">
                <a class="day-nav-btn" :href="yesterdayUrl" title="Previous day">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <div class="day-feed-header-text">
                    <h5 @click="changeDay = true">
                        <span v-if="changeDay">
                            <input type="date" class="day-date-input" v-model="date" @change="redirectDate" />
                        </span>
                        <span v-else>{{ formattedDate }}</span>
                    </h5>
                </div>
                <a class="day-nav-btn" :href="tomorrowUrl" title="Next day">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>

            <div class="day-empty-state" v-show="events.length === 0">
                <i class="fa-solid fa-calendar"></i>
                <p>No activities recorded<br>for this day.</p>
            </div>

            <div class="day-feed-scroll" v-show="events.length > 0">
                <component
                    :is="item.isDivider ? 'feed-divider' : item.isGroup ? 'event-group' : 'event'"
                    v-for="item in groupedFeed"
                    :key="item.isDivider ? 'divider-' + item.label : item.isGroup ? item.color + item.events[0].date : item.id"
                    :group="item"
                    :event="item"
                />
            </div>

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

            <div class="checkin-bar">
                <a href="/locations/checkins/create" class="btn-checkin btn-checkin-primary">
                    <i class="fa-solid fa-location-dot"></i> Check In
                </a>
                <a href="/locations/checkins/pending/create" class="btn-checkin btn-checkin-secondary">
                    <i class="fa-solid fa-crosshairs"></i> Drop a Pin
                </a>
                <button class="btn-checkin btn-checkin-secondary" :class="{ 'btn-checkin-active': showNoteForm }" @click="showNoteForm = !showNoteForm" type="button">
                    <i class="fa-solid fa-note-sticky"></i> Add Note
                </button>
            </div>

        </div>

        <div class="day-map-col">
            <div id="dashboard-map"></div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
import event from './event.vue';
import eventGroup from './event-group.vue';
import feedDivider from './feed-divider.vue';
import item from './item.vue';

function sortEvents(a, b) {
    const datea = new Date(a.date);
    const dateb = new Date(b.date);
    if (datea < dateb) return -1;
    if (datea > dateb) return 1;
    return 0;
}

export default {
    components: {
        event,
        'event-group': eventGroup,
        'feed-divider': feedDivider,
        item,
    },
    props: {
        day: String,
        formattedDate: String,
        yesterdayUrl: String,
        tomorrowUrl: String,
        checkinUrl: String,
        pendingUrl: String,
    },
    data() {
        return {
            bounds: [],
            changeDay: false,
            date: this.day,
            events: [],
            items: [],
            map: null,
            mapLayer: null,
            mapLine: null,
            mapboxToken: import.meta.env.VITE_MAPBOX_TOKEN,
            note: {},
            showNoteForm: false,
            today: this.day === new Date().toISOString().split('T')[0],
        };
    },
    computed: {
        groupedFeed() {
            const sortedEvents = [...this.events].sort(sortEvents);
            const result = [];
            let currentGroup = null;
            for (const e of sortedEvents) {
                if (e.collapsible) {
                    if (currentGroup && currentGroup.color === e.color) {
                        currentGroup.events.push(e);
                    } else {
                        currentGroup = {
                            isGroup: true,
                            color: e.color,
                            groupLabel: e.groupLabel,
                            groupIcon: e.groupIcon,
                            events: [e],
                        };
                        result.push(currentGroup);
                    }
                } else {
                    currentGroup = null;
                    result.push(e);
                }
            }

            const periods = ['Morning', 'Afternoon', 'Evening'];
            const getPeriod = (date) => {
                const hour = new Date(date).getHours();
                if (hour < 12) return 0;
                if (hour < 18) return 1;
                return 2;
            };
            const getDate = (item) => item.isGroup ? item.events[0].date : item.date;

            const withDividers = [];
            let lastPeriod = -1;
            for (const item of result) {
                const period = getPeriod(getDate(item));
                if (period !== lastPeriod) {
                    withDividers.push({ isDivider: true, label: periods[period] });
                    lastPeriod = period;
                }
                withDividers.push(item);
            }

            return withDividers;
        },
    },
    methods: {
        accountResponse(response) {
            const { color, groupLabel, groupIcon, collapsible, events, items, lines, pins } = response.data;
            this.events = this.events.concat(events.map(e => ({ ...e, color, groupLabel, groupIcon, collapsible })));
            this.items = this.items.concat(items);
            lines.forEach((line) => this.addLine(color, line.cords, line.id));
            pins.forEach((pin) => this.addPin(pin));
        },
        accountRequest(account) {
            axios.get('/api/dashboard/' + account.slug + '/' + this.date).then(this.accountResponse);
        },
        addLine(color, cords, id) {
            this.mapLine = L.polyline(cords, { color, weight: 5 })
                .on({
                    mouseover: () => document.getElementById(id).classList.add('border', 'border-primary'),
                    mouseout: () => document.getElementById(id).classList.remove('border', 'border-primary'),
                })
                .addTo(this.map);
            this.bounds.push(this.mapLine.getBounds());
            this.map.fitBounds(this.bounds);
        },
        addPin(pin) {
            this.bounds.push([pin.latitude, pin.longitude]);
            L.marker(L.latLng([pin.latitude, pin.longitude]), { title: pin.title })
                .on({
                    mouseover: () => document.getElementById(pin.id).classList.add('border', 'border-primary'),
                    mouseout: () => document.getElementById(pin.id).classList.remove('border', 'border-primary'),
                })
                .addTo(this.map);
            this.map.fitBounds(this.bounds);
        },
        redirectDate() {
            window.location = '/day/' + this.date.replaceAll('-', '/');
        },
        resetNote() {
            const hhmm = new Date().toTimeString().slice(0, 5);
            this.note = {
                title: '',
                sub_title: '',
                icon: '',
                published_at: this.day + 'T' + hhmm,
                dashboard_visible: true,
            };
            this.showNoteForm = false;
        },
        saveNote() {
            axios.post('/api/notes/dashboard', this.note).then((response) => {
                this.accountResponse(response);
                this.resetNote();
            });
        },
        setupmap() {
            this.map = L.map('dashboard-map').setView([38, -95], 13);
            this.mapLayer = L.layerGroup().addTo(this.map);
            L.tileLayer(
                'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=' + this.mapboxToken,
                {
                    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                    maxZoom: 20,
                    id: 'mapbox/streets-v11',
                    accessToken: this.mapboxToken,
                }
            ).addTo(this.map);
        },
    },
    mounted() {
        this.setupmap();
        this.resetNote();
        axios.get('/api/accounts/user').then((response) => {
            response.data.forEach((account) => this.accountRequest(account));
        });
        axios.get('/api/dashboard/notes/' + this.date).then(this.accountResponse);
        axios.get('/api/dashboard/checkins/' + this.date).then(this.accountResponse);
        axios.get('/api/dashboard/pending_checkins/' + this.date).then(this.accountResponse);
    },
};
</script>
