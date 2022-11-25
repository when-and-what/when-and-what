<template>
    <li :id="event.id" class="list-group-item">{{ event.title }} <br />{{ event.subTitle }}</li>
</template>
<script>
export default {
    props: ['event'],
};
</script>
