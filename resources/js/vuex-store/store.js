import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import membershipPurchase from "./memberships/membershipPurchase";
import checkoutFunnels from "./checkout-funnels/checkoutFunnels";

export default new Vuex.Store({
    modules: {
        checkoutFunnels,
        membershipPurchase
    },
    state() {
        return {
            screenHeight: window.innerHeight,
            screenWidth: window.innerWidth,        };
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
