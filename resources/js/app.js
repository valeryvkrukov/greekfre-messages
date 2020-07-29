require('./bootstrap');

import DataTable from 'laravel-vue-datatable';
import axios from 'axios';
import VueAxios from 'vue-axios';

window.Vue = require('vue');

Vue.use(DataTable);
Vue.use(VueAxios, axios);

Vue.component('error-modal', require('./components/ErrorModal.vue').default);
Vue.component('new-message-form', require('./components/dashboard/NewMessageForm.vue').default);
Vue.component('editable-cell-column', require('./components/dashboard/table-columns/TextEditableColumn.vue').default);
Vue.component('picked-up-column', require('./components/dashboard/table-columns/PickedUpColumn.vue').default);
Vue.component('delete-row-column', require('./components/dashboard/table-columns/DeleteRowColumn.vue').default);
Vue.component('all-messages', require('./components/dashboard/AllMessages.vue').default);
Vue.component('profile-form', require('./components/profile/ProfileForm.vue').default);

const app = new Vue({
    el: '#app'
});
