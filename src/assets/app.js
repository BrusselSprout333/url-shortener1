import './styles/app.css';
require('./bootstrap');

window.Vue = require('vue');
Vue.component('hash-create', require('./views/app.vue').default)

import Vue from 'vue'
console.log('include vue');
const app = new Vue({
    el: '#app',
});
