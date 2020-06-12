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

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('allcommerce-header', require('./components/includes/HeaderComponent.vue').default);
Vue.component('allcommerce-footer', require('./components/includes/FooterComponent.vue').default);
Vue.component('allcommerce-sidebar', require('./components/includes/SidebarComponent.vue').default);

Vue.component('inventory-grid', require('./components/content-grid/InventoryGridComponent.vue').default);
Vue.component('select-post-grid', require('./components/content-grid/SelectToPostGridComponent.vue').default);

Vue.component('shopify-polaris', require('./components/shopify/PolarisContainerComponent.vue').default);
Vue.component('shopify-connect', require('./components/shopify/containers/account/connectAccountContainer.vue').default);
Vue.component('shopify-error', require('./components/shopify/containers/whoops/shopifyErrorContainer.vue').default);

Vue.component('account-does-not-exist', require('./components/shopify/screens/whoops/AccountDoesNotExistScreen.vue').default);
Vue.component('sales-channel-not-installed', require('./components/shopify/screens/whoops/SalesChannelNotInstalled.vue').default);
Vue.component('link-account', require('./components/shopify/screens/account/linkAccountComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import PolarisVue from '@eastsideco/polaris-vue';
Vue.use(PolarisVue);

const app = new Vue({
    el: '#app',
});
