import './bootstrap';

import { createApp } from 'vue';
import locationsmap from './components/locations.vue';
import newlocation from './components/location.vue';
import checkinmap from './components/checkin.vue';
import DashboardApp from './components/dashboard/App.vue';
import RangeApp from './components/range/App.vue';

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
        timezone: dashboardEl.dataset.timezone,
    }).mount(dashboardEl);
}

const rangeEl = document.getElementById('range-container');
if (rangeEl) {
    createApp(RangeApp, {
        start: rangeEl.dataset.start,
        startTime: rangeEl.dataset.startTime,
        end: rangeEl.dataset.end,
        endTime: rangeEl.dataset.endTime,
        formattedStart: rangeEl.dataset.formattedStart,
        formattedEnd: rangeEl.dataset.formattedEnd,
        isMemory: rangeEl.dataset.isMemory === 'true',
        memoryTitle: rangeEl.dataset.title ?? '',
        timezone: rangeEl.dataset.timezone,
    }).mount(rangeEl);
}

const button = document.getElementById('generate-token');
if (button) {
    button.addEventListener('click', function () {
        document.getElementById('pocketcasts-email').innerHTML = document.getElementById('email').value;
        document.getElementById('pocketcasts-password').innerHTML = document.getElementById('password').value;
    });
}

const tzPrompt = document.getElementById('tz-prompt');
if (tzPrompt) {
    const profileTz = tzPrompt.dataset.profileTz;
    const browserTz = Intl.DateTimeFormat().resolvedOptions().timeZone;
    const dismissKey = `tz-prompt-dismissed:${profileTz}:${browserTz}`;

    let dismissed = false;
    try {
        dismissed = localStorage.getItem(dismissKey) !== null;
    } catch (e) {}

    if (browserTz && browserTz !== profileTz && !dismissed) {
        tzPrompt.querySelector('[data-tz-browser]').textContent = browserTz;
        tzPrompt.querySelector('input[name="timezone"]').value = browserTz;
        tzPrompt.hidden = false;

        tzPrompt.querySelector('[data-tz-dismiss]').addEventListener('click', function () {
            try {
                localStorage.setItem(dismissKey, '1');
            } catch (e) {}
            tzPrompt.hidden = true;
        });
    }
}
