<template>
    <div class="dropdown">
        <button 
            class="btn btn-secondary dropdown-toggle"
            type="button"
            id="dropdownPickedUp"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <span v-if="data.picked_up">Yes</span>
            <span v-else>No</span>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownPickedUp">
            <a class="dropdown-item" @click.prevent="changeData(0)">No</a>
            <a class="dropdown-item" @click.prevent="changeData(1)">Yes</a>
        </div>
    </div>
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
        changeData(value) {
            this.data.picked_up = value;
            this.$root.$emit('pickedup', {
                id: this.data.id,
                picked_up: this.data.picked_up
            })
        }
    }
}
</script>