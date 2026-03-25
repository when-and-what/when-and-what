require('./bootstrap');

import axios from 'axios';


import { createApp } from 'vue';
import locationsmap from './components/locations.vue';
import newlocation from './components/location.vue';
import checkinmap from './components/checkin.vue';
import event from './components/dashboard/event.vue';
import eventGroup from './components/dashboard/event-group.vue';
import feedDivider from './components/dashboard/feed-divider.vue';
import item from './components/dashboard/item.vue';
import { map } from 'lodash';

const newLocation = createApp({
    components: {
        newlocation,
        checkinmap,
        locationsmap,
    },
    data() {
        return {
            mapboxToken: process.env.MIX_MAPBOX_TOKEN,
            newLocation: 0,
        };
    },
}).mount('#location-container');
function sortEvents(a, b) {
    var datea = new Date(a.date);
    var dateb = new Date(b.date);
    if (datea < dateb) {
        return -1;
    }
    if (datea > dateb) {
        return 1;
    }
    return 0;
}
const dashboard = createApp({
    components: {
        event,
        'event-group': eventGroup,
        'feed-divider': feedDivider,
        item,
    },
    computed: {
        groupedFeed: function () {
            const sortedEvents = this.events.sort(sortEvents);
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
    data() {
        return {
            bounds: [],
            changeDay: false,
            date: day,
            events: [],
            items: [],
            map: null,
            mapLayer: null,
            mapLine: null,
            mapboxToken: process.env.MIX_MAPBOX_TOKEN,
            note: {},
            showNoteForm: false,
            today: day == new Date().toISOString().split('T')[0],
        };
    },
    methods: {
        accountResponse(response) {
            const color = response.data.color;
            const groupLabel = response.data.groupLabel;
            const groupIcon = response.data.groupIcon ;
            const collapsible = response.data.collapsible;
            const events = response.data.events.map(e => ({
                ...e,
                color,
                groupLabel,
                groupIcon,
                collapsible,
            }));
            this.events = this.events.concat(events);
            this.items = this.items.concat(response.data.items);
            response.data.lines.forEach((line) => {
                this.addLine(response.data.color, line.cords, line.id);
            });
            response.data.pins.forEach((pin) => {
                this.addPin(pin);
            });
        },
        accountRequest(account) {
            axios
                .get('/api/dashboard/' + account.slug + '/' + this.date)
                .then(this.accountResponse);
        },
        addEvent(event) {},
        addLine(color, cords, id) {
            var self = this;
            this.mapLine = L.polyline(cords, { color: color, weight: 5 })
                .on({
                    mouseover: function () {
                        document.getElementById(id).classList.add('border');
                        document.getElementById(id).classList.add('border-primary');
                    },
                    mouseout: function () {
                        document.getElementById(id).classList.remove('border');
                        document.getElementById(id).classList.remove('border-primary');
                    },
                })
                .addTo(self.map);
            self.bounds.push(self.mapLine.getBounds());
            this.map.fitBounds(self.bounds);
        },
        addPin(pin) {
            var latlng = L.latLng([pin.latitude, pin.longitude]);
            self.bounds.push([pin.latitude, pin.longitude]);
            L.marker(latlng, {
                title: pin.title,
            })
                .on({
                    mouseover: function () {
                        document.getElementById(pin.id).classList.add('border');
                        document.getElementById(pin.id).classList.add('border-primary');
                    },
                    mouseout: function () {
                        document.getElementById(pin.id).classList.remove('border');
                        document.getElementById(pin.id).classList.remove('border-primary');
                    },
                })
                .addTo(self.map);
            self.map.fitBounds(self.bounds);
        },
        changeDate() {
            self.changeDay = true;
        },
        redirectDate() {
            window.location = "/day/"+self.date.replaceAll('-', '/');
        },
        resetNote() {
            const now = new Date();
            const hhmm = now.toTimeString().slice(0, 5);
            this.note = {
                title: '',
                sub_title: '',
                icon: '',
                published_at: day + 'T' + hhmm,
                dashboard_visible: true,
            };
            this.showNoteForm = false;
        },
        saveNote() {
            var self = this;
            axios.post('/api/notes/dashboard', this.note).then(function (response) {
                self.accountResponse(response);
                self.resetNote();
            });
        },
        setupmap() {
            var self = this;
            self.map = L.map('dashboard-map').setView([38, -95], 13);
            self.mapLayer = L.layerGroup().addTo(self.map);
            L.tileLayer(
                'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=' +
                    self.mapboxToken,
                {
                    attribution:
                        'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                    maxZoom: 20,
                    id: 'mapbox/streets-v11',
                    accessToken: self.mapboxToken,
                }
            ).addTo(self.map);
        },
    },
    mounted() {
        this.setupmap();
        this.resetNote();
        self = this;
        axios.get('/api/accounts/user').then(function (response) {
            response.data.forEach((account) => {
                self.accountRequest(account);
            });
        });
        axios.get('/api/dashboard/notes/' + self.date).then(this.accountResponse);
        axios.get('/api/dashboard/checkins/' + self.date).then(this.accountResponse);
        axios.get('/api/dashboard/pending_checkins/' + self.date).then(this.accountResponse);
    },
}).mount('#dashboard-container');

const button = document.getElementById('generate-token');
if(button)
{
    button.addEventListener('click', function()
    {
        document.getElementById('pocketcasts-email').innerHTML = document.getElementById('email').value;
        document.getElementById('pocketcasts-password').innerHTML = document.getElementById('password').value;
    });
}
