/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.component('shopify-account-dashboard', require('./components/shopify/account/accountDashboardContainer.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VuexShopify from './vuex-shopify/shopify';
window.shopify = VuexShopify;

import PolarisVue from '@eastsideco/polaris-vue';
Vue.use(PolarisVue);

const app = new Vue({
    el: '#app',
    shopify,
    watch: {},
    data() {
        return {};
    },
    computed: {},
    methods: {},
    mounted() {
        console.log("This Shopify Checkout Experience powered by AllCommerce");
    }
});
