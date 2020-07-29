<template>
    <div class="form-horizontal">
        <error-modal ref="modal"></error-modal>
        <div class="form-row">
            <div class="col mx-md-5">
                <h3>Account settings</h3>
                <div class="form-group">
                    <label class="col control-label">Name:</label>
                    <input type="name" ref="name" v-model="account.name" class="form-control">
                </div>
                <div class="form-group">
                    <label class="col control-label">Email:</label>
                    <input type="email" ref="email" v-model="account.email" class="form-control">
                </div>
                <div class="form-group">
                    <label class="col control-label">Password:</label>
                    <input type="password" ref="password" v-model="account.password" class="form-control">
                </div>
                <div class="form-group">
                    <label class="col control-label">Password Repeat:</label>
                    <input type="password" v-model="account.password_confirmation" class="form-control">
                </div>
                <a class="btn btn-primary" @click.prevent="saveSettings('account')" href="#" role="button">Save</a>
            </div>
            <div class="col mx-md-5">
                <h3>Twilio settings</h3>
                <div class="form-group">
                    <label class="col-sm-6 control-label">Twilio Mobile:</label>
                    <input type="tel" ref="twilio_phone" v-model="twilio.twilio_phone" class="form-control">
                </div>
                <div class="form-group">
                    <label class="col-sm-6 control-label">Twilio SID:</label>
                    <input type="text" ref="twilio_sid" v-model="twilio.twilio_sid" class="form-control">
                </div>
                <div class="form-group">
                    <label class="col-sm-6 control-label">Twilio Token:</label>
                    <input type="text" ref="twilio_token" v-model="twilio.twilio_token" class="form-control">
                </div>
                <div class="form-group">
                    <label class="col-sm-6 control-label">Message template:</label>
                    <textarea ref="default_message" v-model="twilio.default_message" class="form-control"></textarea>
                    <small class="form-text text-muted">
                        Message can contain variables $name, $order and $phone that will be replaced by values from "New Message Form"
                    </small>
                </div>
                <a class="btn btn-primary" @click.prevent="saveSettings('twilio')" href="#" role="button">Save</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'profile-form',
    data () {
        return {
            currentUrl: location.protocol + '//' + location.host + location.pathname,
            account: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
            twilio: {
                twilio_phone: '',
                twilio_sid: '',
                twilio_token: '',
                twilio_default_message: '',
            }
        };
    },
    created () {
        this.loadCurrentProfile();
    },
    methods: {
        loadCurrentProfile() {
            let currentObj = this;
            this.axios.get(this.currentUrl + '/load').then((res) => {
                currentObj.iterateAccountSettings(res.data);
            })
        },
        saveSettings(section) {
            let errorModal = this.$refs.modal;
            if (this.hasOwnProperty(section)) {
                let currentObj = this;
                let data = {
                    _section: section,
                    data: this[section]
                };
                this.axios.post(this.currentUrl, data).then((res) => {
                    if (res.error) {
                        //
                    } else {
                        currentObj.iterateAccountSettings(res.data);
                    }
                }).catch((err) => {
                    errorModal.title = 'Network error';
                    errorModal.error = err;
                    $(errorModal.$el).modal();
                });
            } else {
                errorModal.title = 'Error';
                errorModal.error = 'Unknown error';
                $(errorModal.$el).modal();
            }
        },
        iterateAccountSettings(data) {
            Object.entries(data).forEach((obj, k) => {
                if (this.hasOwnProperty(obj[0])) {
                    this[obj[0]] = obj[1];
                }
            });
        }
    }
}
</script>
