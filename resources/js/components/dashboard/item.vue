<template>
    <div class="col">{{ item.icon }} {{ item.name }}<br />{{ item.value }}</div>
</template>
<script>
export default {
    props: ['item'],
};
</script>
