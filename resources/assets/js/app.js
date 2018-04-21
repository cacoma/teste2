/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import VueTheMask from 'vue-the-mask';
import money from 'v-money';
import racaz from './javascript/js.js';
import VueCharts from 'vue-chartjs';
import VueChartkick from 'vue-chartkick';
import Chart from 'chart.js'


//import moment from "moment";
Vue.use(money, {
  precision: 4
});
Vue.use(VueTheMask);
Vue.use(BootstrapVue);
Vue.use(racaz);
Vue.use(VueChartkick, {adapter: Chart})

require('./bootstrap');

window.Vue = require('vue');
window.moment = require('moment');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
//Vue.component('welcomecaroussel', require('./components/welcomeCaroussel.vue'));
Vue.component('navbaradmin', require('./components/layouts/navbaradmin.vue'));
Vue.component('index', require('./components/index.vue'));
Vue.component('homechart', require('./components/layouts/homeChart.vue'));
Vue.component('homechartpizza', require('./components/layouts/homeChartPizza.vue'));
Vue.component('homescreen', require('./components/layouts/homeScreen.vue'));
Vue.component('homechartint', require('./components/layouts/homeChartInt.vue'));
//Vue.component('helpers', require('./components/helpers/helpers.vue'));

const app = new Vue({
  el: '#app'
});
