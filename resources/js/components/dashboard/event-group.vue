<template>
    <div class="activity-group">
        <div class="activity-item collapsible" @click="collapsed = !collapsed" style="cursor:pointer">
            <div class="activity-dot" :style="dotStyle">{{ group.groupIcon }}</div>
            <div class="activity-body">
                <div class="activity-title">{{ group.events.length }} {{ group.groupLabel }}</div>
                <div class="activity-sub" v-if="sharedSubTitle">
                    <a v-if="sharedSubTitleLink" :href="sharedSubTitleLink">{{ sharedSubTitle }}</a>
                    <span v-else>{{ sharedSubTitle }}</span>
                </div>
            </div>
            <div class="activity-time" style="display:flex;flex-direction:column;align-items:flex-end;">
                <div>{{ displayTime(group.events[0].date) }}</div>
                <i class="fa-solid" :class="collapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
            </div>
        </div>
        <div v-if="!collapsed" class="activity-group-items">
            <event v-for="e in group.events" :event="e" :key="e.id" />
        </div>
    </div>
</template>
<script>
import event from './event.vue';
export default {
    components: { event },
    props: ['group'],
    data() {
        return { collapsed: true };
    },
    methods: {
        displayTime(datetime) {
            return new Date(datetime).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        },
    },
    computed: {
        dotStyle() {
            const color = this.group.color;
            return { background: color + '22', color };
        },
        sharedSubTitle() {
            const titles = this.group.events.map(e => e.subTitle).filter(Boolean);
            if (titles.length !== this.group.events.length) return null;
            const first = titles[0];
            return titles.every(t => t === first) ? first : null;
        },
        sharedSubTitleLink() {
            if (!this.sharedSubTitle) return null;
            const first = this.group.events[0].subTitleLink;
            return first && this.group.events.every(e => e.subTitleLink === first) ? first : null;
        },
    },
};
</script>
