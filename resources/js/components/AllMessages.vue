<template>
    <div>
        <new-message-form></new-message-form>
        <div class="container">
            <data-table
                ref="messagesTable"
                :columns="columns"
                order-dir="desc"
                url="/messages"
            ></data-table>
        </div>
    </div>
</template>

<script>
import NewMessageForm from './NewMessageForm';
import TextEditableColumn from './TextEditableColumn';
import PickedUpColumn from './PickedUpColumn';
import DeleteRowColumn from './DeleteRowColumn';

export default {
    components: {
        NewMessageForm,
        TextEditableColumn,
        PickedUpColumn,
        DeleteRowColumn,
    },
    data() {
        return {
            data: {},
            tableProps: {
                search: '',
                length: 10,
                column: 'id',
                dir: 'asc'
            },
            columns: [
                {
                    label: 'ID',
                    name: 'id',
                    orderable: true,
                },
                {
                    label: 'Name',
                    name: 'name',
                    orderable: true,
                    component: TextEditableColumn,
                },
                {
                    label: 'Order #',
                    name: 'order',
                    orderable: true,
                    component: TextEditableColumn,
                },
                {
                    label: 'Phone #',
                    name: 'phone',
                    orderable: true,
                    component: TextEditableColumn,
                },
                {
                    label: 'Message',
                    name: 'message',
                    orderable: false,
                    component: TextEditableColumn,
                },
                {
                    label: 'Sent Time',
                    name: 'created_at',
                    orderable: true,
                    component: TextEditableColumn,
                },
                {
                    label: 'Picked Up?',
                    name: 'picked_up',
                    orderable: true,
                    component: PickedUpColumn,
                },
                {
                    label: '',
                    name: 'actions',
                    orderable: false,
                    component: DeleteRowColumn,
                },
            ]
        }
    },
    created () {
        this.$root.$on('pickedup', (data) => this.updateRecord(data));
        this.$root.$on('deleterow', (id) => this.deleteRecord(id));
        this.$root.$on('cellupdated', (data) => this.updateRecord(data))
    },
    methods: {
        updateRecord(data) {
            let currentObj = this;
            this.axios.put('/messages', data).then(function (response) {
                currentObj.$refs.messagesTable.getData();
            }).catch(function (error) {
                console.log(error)
            });
        },
        deleteRecord(id) {
            let currentObj = this;
            this.axios.delete('/messages/' + id + '/delete').then(function (response) {
                currentObj.$refs.messagesTable.getData();
            }).catch(function (error) {
                console.log(error)
            });
        }
    }
}
</script>