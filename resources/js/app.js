import Vue from 'vue';
import store from './store/index'
import Base from './base';
import axios from 'axios';
import Routes from './routes';
import VueRouter from 'vue-router';
import VueJsonPretty from 'vue-json-pretty';
import moment from 'moment-timezone';
import Swal from 'sweetalert2'
import Paginate from 'vuejs-paginate'

import axiosResponseInterceptor from './functions'

require('bootstrap');
require('@fortawesome/fontawesome-free/js/all');
Vue.config.productionTip = false
Vue.use(VueRouter);
Vue.mixin(Base);

window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');
window.axios = axios;
axiosResponseInterceptor(window.axios)
window.Swal = Swal;
window.moment = moment;

const router = new VueRouter({
    routes: Routes,
    mode: 'hash',
    base: '/',
});

Vue.component('paginate', Paginate)
Vue.use(store);

new Vue({
	router,
	store,
	el: '#app',
})
