<template>
    <div v-html="html"></div>
</template>
<script>
export default {
    data() {
        return {
            html: this.text.replace(/#(\w+)/g, '<a href="/tags/$1">#$1</a>'),
        };
    },
    props: ['text'],
};
</script>
