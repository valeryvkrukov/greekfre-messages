<template>
    <div>
        <div v-if="isEdit === false && name != 'created_at'" :class="classes" @dblclick="setToEditMode(data)">{{ data[name] }}</div>
        <div v-else-if="isEdit === false && name == 'created_at'" :class="classes" @dblclick="setToEditMode(data)">{{ dateFormatter(data[name]) }}</div>
        <div v-else :class="classes">
            <input v-if="inputType == 'text'" :ref="'input-' + name" :type="inputType" class="form-control" :value="data[name]" @blur="setToViewMode(data)">
            <textarea v-else-if="inputType == 'textarea'" :ref="'input-' + name" :value="data[name]" @blur="setToViewMode(data)"></textarea>
            <datepicker v-else-if="inputType == 'datepicker'" :format="dateFormatter" :ref="'input-' + name" :value="data[name]" @selected="dateValueChanged" :bootstrap-styling="true"></datepicker>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';

export default {
    components: {
        Datepicker
    },
    props: {
        data: {},
        name: {},
        classes: {},
        dblclick: {
            type: Function,
            default: () => {
            }
        }
    },
    data () {
        return {
            isEdit: false,
            inputType: 'text',
            dateValue: null,
        }
    },
    created () {
        if (this.name === 'created_at') {
            this.dateValue = this.data['created_at'];
        }
    },
    methods: {
        dateFormatter(date) {
            return moment(date).format('YYYY-MM-DD');
        },
        dateValueChanged(value) {
            let newData = {
                id: this.data.id,
            };
            newData[this.name] = value;
            this.emitChanges(newData)
        },
        setToEditMode(data) {
            this.isEdit = !this.isEdit;
            if (this.name === 'created_at') {
                this.inputType = 'datepicker';
            } else {
                if (this.name === 'message') {
                    this.inputType = 'textarea'
                }
                this.$nextTick(function() {
                    this.$refs['input-' + this.name].focus();
                });
            }
        },
        setToViewMode(data) {
            let newData = {
                id: this.data.id,
            };
            let newValue = this.$refs['input-' + this.name].value;
            newData[this.name] = newValue;
            this.emitChanges(newData);
        },
        emitChanges(data) {
            this.isEdit = false;
            this.$root.$emit('cellupdated', data);
        }
    }
}
</script>