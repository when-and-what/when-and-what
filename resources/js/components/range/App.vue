<template>
    <div class="day-stats-bar">
        <span v-if="isMemory" class="fw-bold day-stat">{{ memoryTitle }}</span>
        <item v-for="stat in items" :item="stat" :key="stat.name" />
    </div>

    <div class="day-layout">

        <div class="day-feed">

            <div class="day-feed-header">
                <div class="day-feed-header-text">
                    <h5>{{ formattedStart }} – {{ formattedEnd }}</h5>
                </div>
            </div>

            <div class="day-range-picker" v-if="!isMemory">
                <input type="date" class="day-date-input" v-model="rangeStart" @change="goToRange" />
                <span class="day-range-sep">to</span>
                <input type="date" class="day-date-input" v-model="rangeEnd" @change="goToRange" />
            </div>

            <div class="day-empty-state" v-show="events.length === 0">
                <i class="fa-solid fa-calendar"></i>
                <p>No activities recorded<br>for this range.</p>
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

        </div>

        <div class="day-map-col">
            <div id="range-map"></div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
import { useMap } from '../../composables/useMap.js';
import { sortEvents } from '../../dashboard-helpers.js';
import event from '../dashboard/event.vue';
import eventGroup from '../dashboard/event-group.vue';
import feedDivider from '../dashboard/feed-divider.vue';
import item from '../dashboard/item.vue';

export default {
    components: {
        event,
        'event-group': eventGroup,
        'feed-divider': feedDivider,
        item,
    },
    props: {
        start: String,
        startTime: { type: String, default: '00:00:00' },
        end: String,
        endTime: { type: String, default: '23:59:59' },
        formattedStart: String,
        formattedEnd: String,
        isMemory: { type: Boolean, default: false },
        memoryTitle: {type: String, default: ''}
    },
    setup() {
        return useMap('range-map');
    },
    data() {
        return {
            events: [],
            items: [],
            rangeStart: this.start,
            rangeEnd: this.end,
            allDayNotes: {},
        };
    },
    computed: {
        groupedFeed() {
            const sorted = [...this.events].sort(sortEvents);
            const result = [];
            let currentGroup = null;

            for (const e of sorted) {
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

            const getDate = (i) => i.isGroup ? i.events[0].date : i.date;
            const toDateKey = (d) => new Date(d).toLocaleDateString([], { weekday: 'long', month: 'long', day: 'numeric' });

            const toDateIso = (d) => new Date(d).toLocaleDateString('en-CA'); // YYYY-MM-DD

            const withDividers = [];
            let lastDay = null;
            for (const i of result) {
                const day = toDateKey(getDate(i));
                if (day !== lastDay) {
                    const iso = toDateIso(getDate(i));
                    withDividers.push({ isDivider: true, label: day, date: iso, allDayNote: this.allDayNotes[iso] ?? null });
                    lastDay = day;
                }
                withDividers.push(i);
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
            this.fitBounds();
        },
        goToRange() {
            if (this.rangeStart && this.rangeEnd && this.rangeEnd >= this.rangeStart) {
                window.location = '/range/' + this.rangeStart + '/' + this.rangeEnd;
            }
        },
        accountRequest(account) {
            axios.get(`/api/range/${account.slug}/${this.start}/${this.end}`, {
                params: { start_time: this.startTime, end_time: this.endTime },
            }).then(this.accountResponse);
        },
    },
    mounted() {
        this.setup();
        const times = { params: { start_time: this.startTime, end_time: this.endTime } };
        axios.get('/api/accounts/user').then((response) => {
            response.data.forEach((account) => this.accountRequest(account));
        });
        axios.get(`/api/range/notes/${this.start}/${this.end}`, times).then(this.accountResponse);
        axios.get(`/api/range/all-day-notes/${this.start}/${this.end}`).then((r) => { this.allDayNotes = r.data; });
        axios.get(`/api/range/checkins/${this.start}/${this.end}`, times).then(this.accountResponse);
        axios.get(`/api/range/pending_checkins/${this.start}/${this.end}`, times).then(this.accountResponse);
    },
};
</script>
