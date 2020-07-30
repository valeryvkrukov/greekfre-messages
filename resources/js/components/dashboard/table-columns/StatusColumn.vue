<template>
    <div>{{ statusValue }}</div>
</template>

<script>
    export default {
        name: 'StatusColumn',
        props: {
            data: {},
            name: {},
            click: {},
            classes: {}
        },
        computed: {
            statusValue() {
                return this.data.status !== null ? this.data.status.status : 'broken';
            }
        }
    }
</script>

<style scoped>

</style>
