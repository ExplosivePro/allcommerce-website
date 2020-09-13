import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import shopify from "./shopify/shopify";

const shopDash = {
    namespaced: true,
    modules: {
        shopify
    },
    state() {
        return {
            loadedShop: ''
        };
    },
    mutations: {
        setActiveShop(state, shop) {
            console.log('Setting State Shop to - ', shop);
            state.loadedShop = shop;
        }
    },
    getters: {

    },
    actions: {
        getShopifyInstallStatus(context) {
            context.dispatch('shopify/getInstallStatus', context.state.loadedShop.id);
        }
    }
};

export default shopDash;
