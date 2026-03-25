<template>
    <div :id="event.id" class="activity-item" :class="{collapsible: collapsible}">
        <div class="activity-dot" :style="dotStyle" v-html="event.icon"></div>
        <div class="activity-body">
            <div class="activity-title">
                <a v-if="event.titleLink" :href="event.titleLink" v-html="tagLinks(event.title)"></a>
                <span v-else v-html="tagLinks(event.title)"></span>
            </div>
            <div class="activity-sub" v-if="event.subTitle">
                <a v-if="event.subTitleLink" :href="event.subTitleLink">{{ event.subTitle }}</a>
                <span v-else v-html="tagLinks(event.subTitle)"></span>
            </div>
        </div>
        <div class="activity-time">
            <a v-if="event.dateLink" :href="event.dateLink">{{ displayTime(event.date) }}</a>
            <span v-else>{{ displayTime(event.date) }}</span>
        </div>
    </div>
</template>
<script>
export default {
    computed: {
        dotStyle() {
            const color = this.event.color || '#0d9488';
            return {
                background: color + '22',
                color: color,
            };
        },
        collapsible() {
            return this.event.collapsible;
        }
    },
    methods: {
        displayTime(datetime) {
            var d = new Date(datetime);
            return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        },
        tagLinks(text) {
            if (!text) return '';
            return text.replace(/#(\w+)/g, '<a href="/tags/$1">#$1</a>');
        },
    },
    props: ['event'],
};
</script>
