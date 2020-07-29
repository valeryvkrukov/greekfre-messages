<template>
    <div>
        <new-message-form></new-message-form>
        <div class="container">
            <data-table
                ref="messagesTable"
                :columns="columns"
                order-dir="desc"
                :url="currentUrl + 'messages'"
            ></data-table>
        </div>
    </div>
</template>

<script>
import NewMessageForm from './NewMessageForm';
import TextEditableColumn from './table-columns/TextEditableColumn';
import PickedUpColumn from './table-columns/PickedUpColumn';
import DeleteRowColumn from './table-columns/DeleteRowColumn';

export default {
    components: {
        NewMessageForm,
        TextEditableColumn,
        PickedUpColumn,
        DeleteRowColumn,
    },
    data() {
        return {
            currentUrl: location.protocol + '//' + location.host + location.pathname,
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
                    label: 'Status',
                    name: 'status.status',
                    orderable: false,
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
        this.$root.$on('newrecord', (data) => this.recordAdded(data));
        this.$root.$on('pickedup', (data) => this.updateRecord(data));
        this.$root.$on('deleterow', (id) => this.deleteRecord(id));
        this.$root.$on('cellupdated', (data) => this.updateRecord(data));
    },
    methods: {
        recordAdded(data) {
            console.log(data)
            Echo.private(`sms_delivery.${data.status.message_sid}`)
                .listen('DeliveryStatusUpdated', (e) => {
                    console.log('ECHO', e)
                    this.updateTable();
                });
            this.updateTable();
        },
        updateRecord(data) {
            let currentObj = this;
            this.axios.put(this.currentUrl + 'messages', data).then(function (response) {
                currentObj.updateTable();
            }).catch(function (error) {
                console.log(error)
            });
        },
        deleteRecord(id) {
            let currentObj = this;
            this.axios.delete(this.currentUrl + 'messages/' + id + '/delete').then(function (response) {
                currentObj.updateTable();
            }).catch(function (error) {
                console.log(error)
            });
        },
        updateTable(e) {
            this.$refs.messagesTable.getData();
        }
    }
}
</script>
