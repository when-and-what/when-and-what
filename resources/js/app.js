require('./bootstrap');

import Alpine from 'alpinejs';
import axios from 'axios';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from 'vue';
import newlocation from './components/location.vue';
import checkinmap from './components/checkin.vue';
import event from './components/dashboard/event.vue';
import item from './components/dashboard/item.vue';
import { map } from 'lodash';

const newLocation = createApp({
    components: {
        newlocation,
        checkinmap,
    },
    data() {
        return {
            mapboxToken: process.env.MIX_MAPBOX_TOKEN,
            newLocation: 0,
        };
    },
}).mount('#location-container');

const dashboard = createApp({
    components: {
        event,
        item,
    },
    data() {
        return {
            bounds: [],
            date: day,
            events: [],
            items: [],
            map: null,
            // mapBounds: [],
            mapLayer: null,
            mapLine: null,
            mapboxToken: process.env.MIX_MAPBOX_TOKEN,
        };
    },
    methods: {
        accountResponse(response) {
            this.events = this.events.concat(response.data.events);
            this.items = this.items.concat(response.data.items);
            response.data.lines.forEach((line) => {
                this.addLine(response.data.color, line.cords);
            });
        },
        accountRequest(account) {
            axios
                .get('/api/dashboard/' + account.slug + '/' + this.date)
                .then(this.accountResponse);
        },
        addEvent(event) {},
        addLine(color, cords) {
            var self = this;
            this.mapLine = L.polyline(cords, { color: color, weight: 2 }).addTo(self.map);
            self.bounds.push(self.mapLine.getBounds());
            this.map.fitBounds(self.bounds);
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
        self = this;
        axios.get('/api/accounts/user').then(function (response) {
            response.data.forEach((account) => {
                self.accountRequest(account);
            });
        });
        axios.get('/api/dashboard/checkins/' + self.date).then(function (response) {
            response.data.events.forEach((event) => {
                self.events.push(event);
            });
            response.data.pins.forEach((pin) => {
                var latlng = L.latLng([pin.latitude, pin.longitude]);
                console.log(latlng);
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
            });
        });
    },
}).mount('#dashboard-container');
