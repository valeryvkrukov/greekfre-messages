require('./bootstrap');

import DataTable from 'laravel-vue-datatable';
import axios from 'axios';
import VueAxios from 'vue-axios';

window.Vue = require('vue');

Vue.use(DataTable);
Vue.use(VueAxios, axios);

Vue.component('new-message-form', require('./components/NewMessageForm.vue').default);
Vue.component('editable-cell-column', require('./components/TextEditableColumn.vue').default);
Vue.component('picked-up-column', require('./components/PickedUpColumn.vue').default);
Vue.component('delete-row-column', require('./components/DeleteRowColumn.vue').default);
Vue.component('all-messages', require('./components/AllMessages.vue').default);

const app = new Vue({
    el: '#app'
});
