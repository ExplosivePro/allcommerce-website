require('./bootstrap');
const Vue = require('vue');
import Vuex from 'vuex';
Vue.use(Vuex);

import SweetModal from 'sweet-modal-vue/src/plugin.js'
Vue.use(SweetModal)

Vue.component('credit-token-purchase', require('./components/memberships/CreditTokenPurchase.vue').default);
Vue.component('membership-purchase', require('./components/memberships/MembershipPurchase.vue').default);
Vue.component('token-membership-toggle', require('./components/memberships/TokenMembershipToggle.vue').default);

Vue.component('dashboard-raffles', require('./components/dashboard/RaffleListings.vue').default);
Vue.component('dashboard-search', require('./components/dashboard/DashboardSearchBar.vue').default);
Vue.component('image-upload', require('./components/image-upload/ImageUploadTextComponent.vue').default);
Vue.component('shop-product-select', require('./components/checkout-funnels/ShopProductSelectComponent.vue').default);



Vue.component('icons-field', require('./screens/icons/iconsField.vue').default);
Vue.component('toggle-sms', require('./screens/sms/ToggleSMSButton.vue').default);

import { createPopper } from '@popperjs/core';

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
            windowHeight: window.innerHeight,
            windowWidth: window.innerWidth
        }
    },
    computed: {},
    methods: {
        ...mapActions({
            setScreenSize: 'setScreenSize'
        }),
        onResize() {
            this.windowHeight = window.innerHeight;
            this.windowWidth = window.innerWidth;
        },
        init() {
            // console.log('Setting tool tips')
            $('[data-toggle="tooltip"]').tooltip();
        },
        dropToggle(elem) {

        }
    },
    mounted() {
        console.log('Welcome to the drawing!')

        this.setScreenSize({
            height: this.windowHeight,
            width: this.windowWidth
        });
        this.$nextTick(() => {
            // this.init();
            window.addEventListener('resize', this.onResize);
        });

        $('button[data-toggle=sidebar-lg-show]').click(function(elem) {
            let curClass = $('body').attr('class')
            console.log(curClass);

            if(curClass.includes('sidebar-lg-show')) {
                curClass = curClass.replace('sidebar-lg-show', '');
            }
            else {
                curClass = curClass + ' sidebar-lg-show'
            }

            $('body').attr('class', curClass)
        });

        $('button[data-toggle=sidebar-show]').click(function(elem) {
            let curClass = $('body').attr('class')
            console.log(curClass);

            if(curClass.includes('sidebar-show')) {
                curClass = curClass.replace('sidebar-show', '');
            }
            else {
                curClass = curClass + ' sidebar-show'
            }

            $('body').attr('class', curClass)
        });

        $('.nav-item.nav-dropdown').click(function (elem) {
            let c = $(elem.currentTarget);
            if(c.attr('class').includes('open'))
            {
                c.attr('class', 'nav-item nav-dropdown');
            }
            else
            {
                c.attr('class', 'nav-item nav-dropdown open');
            }

        })
        /*
        $('.nav-dropdown').click(function () {
            $('.nav-dropdown').attr('class', 'nav-item nav-dropdown open');
        })
         */
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.onResize);
    }
});
