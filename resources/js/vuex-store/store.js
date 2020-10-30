import Vue from 'vue';
import Vuex from 'vuex';

import dashboard from './dashboard';
import shopDash from './shop-dashboard';
import kpi from './kpi';
import asidebar from "./asidebar/asidebar";
import smsManager from "./managerModules/smsManager";
import paymentGatewaysManager from "./managerModules/paymentGatewaysManager";
import checkoutFunnelsManager from "./managerModules/checkoutFunnelsManager";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        asidebar,
        dashboard,
        kpi,
        shopDash,
        smsManager,
        paymentGatewaysManager,
        checkoutFunnelsManager
    },
    state() {
        return {
            screenHeight: window.innerHeight,
            screenWidth: window.innerWidth,
        };
    },
    mutations: {
        screenHeight(state, height) {
            console.log('Mutating screenHeight to ' +height);
            state.screenHeight = height;
        },
        screenWidth(state, width) {
            state.screenWidth = width;
        }
    },
    getters: {
        screenHeight(state) {
            return state.screenHeight;
        },
        screenWidth(state) {
            return state.screenWidth;
        },
    },
    actions: {
        setScreenSize({ commit }, size) {
            commit('screenHeight', size['height']);
            commit('screenWidth', size['width']);
        }
    }
});
