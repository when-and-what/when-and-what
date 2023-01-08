<template>
    <li :id="event.id" class="list-group-item d-flex">
        <div class="ms-2 me-auto">
            {{ event.icon }} {{ event.title }} <br />{{ event.subTitle }}
        </div>
        <span>{{ displayTime(event.date) }}</span>
    </li>
</template>
<script>
export default {
    methods: {
        displayTime(datetime) {
            var d = new Date(datetime);
            return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        },
    },
    props: ['event'],
};
</script>
