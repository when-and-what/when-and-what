<template>
    <div id="mapid" style="height: 500px; width: 500px">Map</div>
    <div class="row">
        <div class="col">
            <input
                type="number"
                name="latitude"
                v-model="lat"
                class="form-control"
                @input="$emit('update:latitude', $event.target.value)"
                step="any"
            />
        </div>
        <div class="col">
            <input
                type="number"
                name="longitude"
                v-model="lng"
                class="form-control"
                @input="$emit('update:longitude', $event.target.value)"
                step="any"
            />
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            lat: null,
            lng: null,
            marker: null,
        };
    },
    emits: ['update:latitude', 'update:longitude'],
    methods: {
        getLocations: function () {
            // alert('yay');
        },
        setupMap() {
            var self = this;
            var mymap = L.map('mapid').setView([self.lat, self.lng], 15);
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
            ).addTo(mymap);
            self.marker = L.marker([self.lat, self.lng], {
                draggable: self.draggable,
            }).addTo(mymap);
            if (self.locations) {
                self.getLocations();
            }
            self.marker.addEventListener('move', function () {
                var center = self.marker.getLatLng();
                self.lat = center.lat;
                self.lng = center.lng;
            });
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
    },
    props: {
        draggable: Boolean,
        locations: Boolean,
        latitude: Number,
        longitude: Number,
    },
};
</script>
