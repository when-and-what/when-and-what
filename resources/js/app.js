import './bootstrap';

import { createApp } from 'vue';
import locationsmap from './components/locations.vue';
import newlocation from './components/location.vue';
import checkinmap from './components/checkin.vue';
import DashboardApp from './components/dashboard/App.vue';

const locationEl = document.getElementById('location-container');
if (locationEl) {
    createApp({
        components: { newlocation, checkinmap, locationsmap },
        data() {
            return {
                mapboxToken: import.meta.env.VITE_MAPBOX_TOKEN,
                newLocation: 0,
            };
        },
    }).mount(locationEl);
}

const dashboardEl = document.getElementById('dashboard-container');
if (dashboardEl) {
    createApp(DashboardApp, {
        day: dashboardEl.dataset.day,
        formattedDate: dashboardEl.dataset.formattedDate,
        yesterdayUrl: dashboardEl.dataset.yesterdayUrl,
        tomorrowUrl: dashboardEl.dataset.tomorrowUrl,
    }).mount(dashboardEl);
}

const button = document.getElementById('generate-token');
if (button) {
    button.addEventListener('click', function () {
        document.getElementById('pocketcasts-email').innerHTML = document.getElementById('email').value;
        document.getElementById('pocketcasts-password').innerHTML = document.getElementById('password').value;
    });
}
