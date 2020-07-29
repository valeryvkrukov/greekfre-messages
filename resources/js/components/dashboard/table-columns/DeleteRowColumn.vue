<template>
    <button @click.prevent="deleteRecord" type="button" class="btn btn-danger">Delete</button>
</template>

<script>
export default {
    props: {
        data: {},
        name: {},
        click: {},
        classes: {}
    },
    methods: {
        deleteRecord(e) {
            this.$root.$emit('deleterow', this.data.id);
        }
    }
}
</script>