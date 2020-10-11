import Vue from 'vue';
import Vuex from 'vuex';

import dashboard from './dashboard';
import shopDash from './shop-dashboard';
import kpi from './kpi';
import asidebar from "./asidebar/asidebar";
import smsManager from "./managerModules/smsManager";
import paymentGatewaysManager from "./managerModules/paymentGatewaysManager";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        asidebar,
        dashboard,
        kpi,
        shopDash,
        smsManager,
        paymentGatewaysManager
    },
    state: {
        count: 2
    },
    mutations: {

    },
    getters: {

    },
    actions: {

    }
});
