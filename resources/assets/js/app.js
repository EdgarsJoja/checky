
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


import Vuetify from 'vuetify';

window.Vue = require('vue');

Vue.use(Vuetify);
Vue.use(require('vue-resource'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Layouts
Vue.component('sidebar', require('./components/layout/Sidebar'));
Vue.component('toolbar', require('./components/layout/Toolbar'));

Vue.component('itemsList', require('./components/ItemsList'));
Vue.component('addItem', require('./components/AddItem'));


const app = new Vue({
    el: '#app'
});
