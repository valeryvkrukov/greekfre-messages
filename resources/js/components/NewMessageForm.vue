<template>
    <div class="container">
        <form id="new_record_form" @submit.prevent="addRecord" method="post" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">Name:</label>
                <div class="col-sm-5">
                    <input type="name" @blur="replaceNameInMessage" v-model="name" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Order # (last 4 digits):</label>
                <div class="col-sm-5">
                    <input type="number" v-model="order" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Mobile #:</label>
                <div class="col-sm-5">
                    <input type="tel" v-model="phone" class="form-control" placeholder="Mobile_No">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Message:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" v-model="message"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="submit" class="btn btn-primary" value="Send">
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
            name: '',
            order: '',
            phone: '',
            message: '',
            defaultMessage: 'Hi, $name! Some message message.',
        }
    },
    created () {
        this.message = this.defaultMessage;
    },
    methods: {
        addRecord(e) {
            e.preventDefault();
            let currentObj = this;
            const newMessageUrl = location.protocol + '//' + location.host + location.pathname + 'messages';
            this.axios.post(newMessageUrl, {
                name: this.name,
                order: this.order,
                phone: this.phone,
                message: this.message,
            }).then(function (response) {
                currentObj.output = response.data;
                currentObj.$root.$emit('newrecord', response.data);
            }).catch(function (error) {
                currentObj.output = error;
            });
        },
        replaceNameInMessage() {
            this.message = this.message.replace('$name', this.name);
        }
    }
}
</script>
