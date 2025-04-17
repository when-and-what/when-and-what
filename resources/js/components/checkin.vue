<template>
    <div v-if="location_warning" class="alert alert-warning" role="alert">
        Zoom in to view all locations
    </div>
    <div id="mapid" style="height: 500px; width: 100%">Map</div>
    <input type="hidden" name="location" v-model="location_id" />
    <div class="input-group">
        <input type="text" class="form-control" v-model="location_name" placeholder="Select a location" />
        <div v-if="new_checkin" class="input-group-text">
            <a href="#" @click.capture="savePendingLocation" title="Save as pending checkin">🔖</a>
        </div>
        <input type="hidden" name="latitude" v-model="lat" />
        <input type="hidden" name="longitude" v-model="lng" />
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
    },
    emits: ['update:latitude', 'update:longitude'],
    methods: {
        savePendingLocation: function () {
            axios.post('/api/locations/checkins/pending', {
                latitude: this.lat,
                longitude: this.lng,
            }).then(function(response) {
                window.location = '/locations/checkins/pending/'+response.data.id+'/edit';
            });
        },
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
                console.log(response.data.total);
                if (response.data.from == response.data.last_page) {
                    self.location_warning = false;
                } else if (response.data.total > 0) {
                    self.location_warning = true;
                }
                response.data.data.forEach((location) => {
                    var marker = L.marker([location.latitude, location.longitude], {
                        draggable: false,
                    }).addTo(self.layer);
                    marker.addEventListener('click', function () {
                        self.location_name = location.name;
                        self.location_id = location.id;
                    });
                });
            });
        },
        setupMap(marker) {
            var self = this;
            self.map = L.map('mapid').setView([self.lat, self.lng], 15);
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
        if (self.location) {
            self.location_id = self.location.id;
            self.location_name = self.location.name;
            self.lat = self.location.latitude;
            self.lng = self.location.longitude;
            self.setupMap(true);
        } else if (self.latitude && self.longitude) {
            self.lat = this.latitude;
            self.lng = this.longitude;
            self.setupMap(true);
        } else if (navigator.geolocation) {
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
