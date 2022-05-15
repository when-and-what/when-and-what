require('./bootstrap');

import Alpine from 'alpinejs';
import axios from 'axios';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from 'vue';
import newlocation from './components/location.vue';
import checkinmap from './components/checkin.vue';
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
