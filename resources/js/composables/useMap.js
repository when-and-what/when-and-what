import { ref } from 'vue';

export function useMap(elementId) {
    const map = ref(null);
    const bounds = ref(null);

    function setup() {
        bounds.value = L.latLngBounds();
        map.value = L.map(elementId).setView([38, -95], 13);
        const token = import.meta.env.VITE_MAPBOX_TOKEN;
        L.tileLayer(
            'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=' + token,
            {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 20,
                id: 'mapbox/streets-v11',
                accessToken: token,
            }
        ).addTo(map.value);
    }

    function addPin(pin) {
        bounds.value.extend([pin.latitude, pin.longitude]);
        L.marker(L.latLng([pin.latitude, pin.longitude]), { title: pin.title })
            .on({
                mouseover: () => document.getElementById(pin.id)?.classList.add('border', 'border-primary'),
                mouseout: () => document.getElementById(pin.id)?.classList.remove('border', 'border-primary'),
            })
            .addTo(map.value);
    }

    function addLine(color, cords, id) {
        const line = L.polyline(cords, { color, weight: 5 })
            .on({
                mouseover: () => document.getElementById(id)?.classList.add('border', 'border-primary'),
                mouseout: () => document.getElementById(id)?.classList.remove('border', 'border-primary'),
            })
            .addTo(map.value);
        bounds.value.extend(line.getBounds());
    }

    function fitBounds() {
        if (bounds.value.isValid()) {
            map.value.fitBounds(bounds.value);
        }
    }

    return { map, bounds, setup, addPin, addLine, fitBounds };
}
