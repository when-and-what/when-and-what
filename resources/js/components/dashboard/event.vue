<template>
    <li :id="event.id" class="list-group-item d-flex">
        <div class="ms-2 me-auto">
            <tag :text="event.icon + ' ' + event.title"></tag>
            <tag :text="event.subTitle"></tag>
        </div>
        <a :href="event.dateLink" v-if="event.dateLink"
            ><span>{{ displayTime(event.date) }}</span></a
        >
        <span v-else>{{ displayTime(event.date) }}</span>
    </li>
</template>
<script>
import tag from './../tag.vue';
export default {
    components: { tag },
    methods: {
        displayTime(datetime) {
            var d = new Date(datetime);
            return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        },
    },
    props: ['event'],
};
</script>
