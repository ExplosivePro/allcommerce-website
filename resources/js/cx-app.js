/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
Vue.component('default-checkout-experience', require('./components/shopify/containers/checkout/DefaultCheckoutExperienceContainer.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VuexStore from './checkout-store/checkout-store';
window.store = VuexStore;

import PolarisVue from '@eastsideco/polaris-vue';
Vue.use(PolarisVue);

new Vue({
    el: '#checkoutApp',
    store,
    watch: {},
    data() {
        return {};
    },
    computed: {},
    methods: {},
    mounted() {
        console.log("This Shopify Checkout Experience Powered by AllCommerce");
    }
});
