<template>
    <div class="container">
        <form id="new_record_form" @submit.prevent="addRecord" method="post" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">Name:</label>
                <div class="col-sm-5">
                    <input type="name" v-model="name" @focusin="changeMessage = false" @blur="changeMessage = true" class="form-control" placeholder="Some Name">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Order # (last 4 digits):</label>
                <div class="col-sm-5">
                    <input type="number" v-model="order" @focusin="changeMessage = false" @blur="changeMessage = true" class="form-control" placeholder="1234">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Mobile #:</label>
                <div class="col-sm-5">
                    <input type="tel" v-model="phone" @focusin="changeMessage = false" @blur="changeMessage = true" class="form-control" placeholder="+12345678910">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Message:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" @focus="changeMessage = true" v-model="message"></textarea>
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
            currentUrl: location.protocol + '//' + location.host + location.pathname,
            name: '',
            order: '',
            phone: '',
            //message: '',
            cachedMessage: '',
            defaultMessage: '',
            changeMessage: true
        }
    },
    created () {
        let currentObj = this;
        this.axios.get(this.currentUrl + 'profile/load').then((response) => {
            currentObj.message = response.data.twilio.default_message;
            currentObj.cachedMessage = response.data.twilio.default_message;
            currentObj.defaultMessage = response.data.twilio.default_message;
        }).then(() => {
            if (currentObj.defaultMessage === '') {
                currentObj.message = '';
                currentObj.cachedMessage = '';
                currentObj.defaultMessage = response.data.default_message;
            }
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
                // TODO
                /*if (this.cachedMessage.length > 0) {
                    let direction = this.defaultMessage.length > this.cachedMessage.length;
                    let length = direction ? this.defaultMessage.length : this.cachedMessage.length;
                    for (let i = 0; i < length; i++) {
                        if (this.defaultMessage[i] !== this.cachedMessage[i]) {
                            if (direction) {
                                let first = this.defaultMessage.slice(0, i);
                                let last = this.defaultMessage.slice(i);
                                this.defaultMessage = first + message[i] + last;
                            } else {
                                let first = this.defaultMessage.slice(0, i);
                                let last = this.defaultMessage.slice(i + 1, this.defaultMessage.length);
                                this.defaultMessage = first + last;
                            }
                            break;
                        }
                    }
                }*/
                this.cachedMessage = this.defaultMessage;
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
                currentObj.message = currentObj.defaultMessage;
                currentObj.$root.$emit('newrecord', response.data);
            }).catch((error) => {
                currentObj.output = error;
            });
        }
    }
}
</script>
