<template>
    <div v-if="location_warning" class="alert alert-warning" role="alert">
        Zoom in to view all locations
    </div>
    <div id="mapid" class="locations-map-fill"></div>
    <div>

    </div>
</template>
<script>
export default {
    data() {
        return {
            lat: null,
            lng: null,
            layer: null,
            map: null,
            location_id: null,
            location_name: null,
            location_warning: false,
        };
    },
    props: {
        dragable: false,
        location: null,
        latitude: null,
        longitude: null,
        new_checkin: false,
        zoom: 15,
    },
    emits: ['update:latitude', 'update:longitude'],
    methods: {
        getLocations: function () {
            var self = this;
            self.layer.clearLayers();
            var bounds = self.map.getBounds();
            var url =
                '/api/locations?north=' +
                bounds._northEast.lat +
                '&east=' +
                bounds._northEast.lng +
                '&south=' +
                bounds._southWest.lat +
                '&west=' +
                bounds._southWest.lng;
            axios.get(url).then(function (response) {
                if (response.data.from == response.data.last_page) {
                    self.location_warning = false;
                } else if (response.data.total > 0) {
                    self.location_warning = true;
                }
                response.data.data.forEach((location) => {
                    var marker = L.marker([location.latitude, location.longitude], {
                        draggable: false,
                    }).bindPopup('<h4><a href="/locations/'+location.id+'/edit">'+location.name+'</a></h4>')
                    .addTo(self.layer);
                });
            });
        },
        setupMap(marker) {
            var self = this;
            self.map = L.map('mapid').setView([self.lat, self.lng], self.zoom ? self.zoom : 15);
            self.layer = L.layerGroup().addTo(self.map);
            L.tileLayer(
                'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=' +
                    self.$parent.mapboxToken,
                {
                    attribution:
                        'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                    maxZoom: 20,
                    id: 'mapbox/streets-v11',
                    accessToken: self.$parent.mapboxToken,
                }
            ).addTo(self.map);
            if (marker) {
                var newMarker = L.marker([self.lat, self.lng], {
                    draggable: true,
                    opacity: 0.6,
                }).addTo(self.map);
            }
            self.map.on('zoomend', self.getLocations);
            self.map.on('moveend', self.getLocations);
            self.getLocations();
        },
    },
    mounted: function () {
        var self = this;
        if (navigator.geolocation) {
            console.log('get user location');
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    self.lat = position.coords.latitude;
                    self.lng = position.coords.longitude;
                    self.setupMap(false);
                },
                function (msg) {}
            );
        }
    },
};
</script>
