<template>
    <div class="activity-divider">
        {{ group.label }}
        <a v-if="group.allDayNote" :href="group.allDayNote.url" class="activity-divider-note" title="Day summary">
            <i class="fa-solid fa-note-sticky"></i>
        </a>
    </div>
</template>
<script>
export default { props: ['group'] };
</script>
