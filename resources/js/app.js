/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

const Vue = require('vue');
import Vuex from 'vuex';
window.Vuetify = require('vuetify');

Vue.use(Vuetify);
Vue.use(Vuex);

const opts = {
    icons: {
        iconfont: 'fa', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
    },
};

export default new Vuetify(opts);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('role-ability-assign', require('./components/containers/RoleAbilitySelectContainer.vue').default);
Vue.component('user-client-role-ability-assign', require('./components/containers/UserClientRoleAbilitySelectContainer.vue').default);
Vue.component('main-dashboard', require('./components/containers/dashboards/DefaultDashboardContainer.vue').default);
Vue.component('shop-dashboard', require('./components/containers/dashboards/ShopDashboardContainer.vue').default);

Vue.component('aside-bar', require('./components/containers/asidebar/AsideBarContainer.vue').default);
Vue.component('aside-context-tab', require('./components/containers/asidebar/ContextTabContainer.vue').default);
Vue.component('aside-sms-manager-context-tab', require('./components/containers/managers/asideBarModules/contextTab/SmsManagerContextTabContainer.vue').default);
Vue.component('aside-pg-manager-context-tab', require('./components/containers/managers/asideBarModules/contextTab/PgManagerContextTabContainer.vue').default);


Vue.component('mega-button-card', require('./components/containers/widgets/mega-button/MegaButtonContainer.vue').default);
Vue.component('default-left-widget', require('./components/containers/widgets/default/defaultLeftContainer.vue').default);
Vue.component('default-right-widget', require('./components/containers/widgets/default/defaultRightContainer.vue').default);
Vue.component('default-top-widget', require('./components/containers/widgets/default/defaultTopContainer.vue').default);

Vue.component('sms-manager', require('./components/containers/managers/sms/SMSManagerContainer.vue').default);
Vue.component('payment-gateway-manager', require('./components/containers/managers/paymentGateways/PGManagerContainer.vue').default);
Vue.component('manager-tabbed-links', require('./components/containers/managers/tabbedLinks/ManagerTabbledLinksContainer.vue').default);
Vue.component('hidden-shop-select', require('./components/containers/managers/checkoutFunnels/FunnelShopSelect2Hidden.vue').default);
Vue.component('funnel-shop-products', require('./components/containers/managers/checkoutFunnels/FunnelShopProductsSelect2.vue').default);




//Vue.component('checkbox-grid', require('./components/presenters/CheckboxGridComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VuexStore from './vuex-store/store';
window.store = VuexStore;

import { mapActions } from 'vuex';

new Vue({
    el: '#app',
    store,
    watch: {
        windowHeight(newHeight, oldHeight) {
            console.log(`screen height changed to ${newHeight} from ${oldHeight}`);

            let payload = {
                height: newHeight,
                width: this.windowWidth
            };

            this.setScreenSize(payload);
        },
        windowWidth(newWidth, oldWidth) {
            console.log(`screen width changed to ${newWidth} from ${oldWidth}`);

            let payload = {
                height: this.windowHeight,
                width: newWidth
            };

            this.setScreenSize(payload);
        }
    },
    data() {
        return {
            themeColor: '',
            windowHeight: window.innerHeight,
            windowWidth: window.innerWidth
        };
    },
    methods: {
        ...mapActions({
            setScreenSize: 'setScreenSize'
        }),
        onResize() {
            this.windowHeight = window.innerHeight;
            this.windowWidth = window.innerWidth;
        }
    },
    mounted() {
        this.setScreenSize({
            height: this.windowHeight,
            width: this.windowWidth
        });
        this.$nextTick(() => {
            window.addEventListener('resize', this.onResize);
        });
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.onResize);
    }
});
