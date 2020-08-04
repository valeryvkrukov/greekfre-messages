<template>
    <div class="container">
        <form id="new_record_form" @submit.prevent="addRecord" method="post" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">Name:</label>
                <div class="col-sm-5">
                    <input type="name" v-model="name" class="form-control" placeholder="Some Name">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Order # (last 4 digits):</label>
                <div class="col-sm-5">
                    <input type="number" v-model="order" class="form-control" placeholder="1234">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Mobile #:</label>
                <div class="col-sm-5">
                    <input type="tel" v-model="phone" class="form-control" placeholder="+12345678910">
                </div>
            </div>
            <div class="form-group" v-if="templates.length > 0">
                <label class="col-sm-2 control-label">Message:</label>
                <div class="col-sm-5">
                    <select class="form-control" @change="messageTemplateChanged($event)">
                        <option
                            v-for="(template, key) in templates"
                            :key="key"
                            :value="key"
                        >Template #{{ key + 1 }}</option>
                    </select>
                    <div
                        v-if="templates[currentTemplate]"
                        class="container p-2 mt-2 bg-white h-auto d-inline-block border"
                    >{{ message }}</div>
                </div>
            </div>
            <div v-else class="form-group col-sm-5">
                <h4 class="text-danger bg-white p-3 border">
                    <p>Need at least one message template.</p>
                    <small class="text-muted">Can be added in Profile &gt; Twilio Settings</small>
                </h4>
            </div>
            <div v-if="templates.length > 0" class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="submit" class="btn btn-primary" value="Send">
                    <input @click="refreshStatuses" type="button" name="refresh" class="btn btn-secondary" value="Refresh Statuses">
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: 'new-message-form',
    data () {
        return {
            currentUrl: location.protocol + '//' + location.host + location.pathname,
            name: '',
            order: '',
            phone: '',
            defaultMessage: '',
            templates: [],
            currentTemplate: 0,
        }
    },
    created () {
        let currentObj = this;
        this.axios.get(this.currentUrl + 'profile/load').then((response) => {
            currentObj.defaultMessage = response.data.twilio.templates.length ? response.data.twilio.templates[0].content : '';
            currentObj.templates = response.data.twilio.templates;
        });
    },
    computed: {
        message: {
            get: function() {
                let message = this.defaultMessage;
                ['name', 'order', 'phone'].forEach((field, i) => {
                    if (this[field] != '') {
                        message = message.replace('$' + field, this[field]);
                    }
                });
                return message;
            },
            set: function(message) {
                // ?TODO
            }
        }
    },
    methods: {
        addRecord(e) {
            e.preventDefault();
            let currentObj = this;
            this.axios.post(this.currentUrl + 'messages', {
                name: this.name,
                order: this.order,
                phone: this.phone,
                message: this.message,
            }).then((response) => {
                currentObj.output = response.data;
                currentObj.$root.$emit('newrecord', response.data);
            }).catch((error) => {
                currentObj.output = error;
            });
        },
        messageTemplateChanged(event) {
            this.currentTemplate = event.target.value;
            this.defaultMessage = this.templates[event.target.value].content;
        },
        refreshStatuses(e) {
            this.$root.$emit('refresh-statuses', e);
        }
    }
}
</script>
