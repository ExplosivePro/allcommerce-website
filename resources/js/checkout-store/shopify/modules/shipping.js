import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const shipping = {
    namespaced: true,
    state() {
        return {
            shipping: 0.00
        };
    },
    mutations: {},
    getters: {},
    actions: {}
};

export default shipping;
