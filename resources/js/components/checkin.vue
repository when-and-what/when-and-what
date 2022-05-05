<template>
    <div id="mapid" style="height: 500px; width: 500px">Map</div>
    <input type="hidden" name="location" v-model="location_id" />
    <div>
        <label for="location">Location</label>
        <input type="text" class="form-control" v-model="location_name" />
    </div>
</template>
<script>
export default {
    data() {
        return {
            lat: null,
            lng: null,
            map: null,
            location_id: null,
            location_name: null,
        };
    },
    props: {
        location,
        latitude: null,
        longitude: null,
    },
    emits: ['update:latitude', 'update:longitude'],
    methods: {
        getLocations: function () {
            var self = this;
            axios.get('/json/locations').then(function (response) {
                response.data.forEach((location) => {
                    var marker = L.marker([location.latitude, location.longitude], {
                        draggable: false,
                    }).addTo(self.map);
                    marker.addEventListener('click', function () {
                        self.location_name = location.name;
                        self.location_id = location.id;
                    });
                });
            });
        },
        setupMap() {
            var self = this;
            self.map = L.map('mapid').setView([self.lat, self.lng], 15);
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
            self.getLocations();
        },
    },
    mounted: function () {
        var self = this;
        if (self.latitude && self.longitude) {
            self.lat = this.latitude;
            self.lng = this.longitude;
            self.setupMap();
        } else if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    self.lat = position.coords.latitude;
                    self.lng = position.coords.longitude;
                    self.setupMap();
                },
                function (msg) {}
            );
        }
        if (self.location) {
            self.location_id = self.location.id;
            self.location_name = self.location.name;
        }
    },
};
</script>
