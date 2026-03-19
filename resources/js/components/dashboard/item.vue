<template>
    <div class="day-stat">
        <span class="day-stat-icon">{{ item.icon }}</span>
        <div class="day-stat-text">
            <span class="day-stat-value">{{ item.value }}</span>
            <span class="day-stat-label">{{ item.name }}</span>
        </div>
    </div>
</template>
<script>
export default {
    props: ['item'],
};
</script>
