<template>
    <li :id="event.id" class="list-group-item d-flex">
        <div class="ms-2 me-auto">
            <a
                v-if="event.titleLink"
                :href="event.titleLink"
                v-html="tagLinks(event.icon + ' ' + event.title)"
                class="text-decoration-none"
            ></a>
            <div v-else v-html="tagLinks(event.icon + ' ' + event.title)"></div>
            <a :href="event.subTitleLink" v-if="event.subTitleLink">{{ event.subTitle }}</a>
            <span v-else v-html="tagLinks(event.subTitle)"></span>
        </div>
        <a :href="event.dateLink" v-if="event.dateLink"
            ><span>{{ displayTime(event.date) }}</span></a
        >
        <span v-else>{{ displayTime(event.date) }}</span>
    </li>
</template>
<script>
export default {
    methods: {
        displayTime(datetime) {
            var d = new Date(datetime);
            return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        },
        tagLinks(text) {
            return text.replace(/#(\w+)/g, '<a href="/tags/$1">#$1</a>');
        },
    },
    props: ['event'],
};
</script>
