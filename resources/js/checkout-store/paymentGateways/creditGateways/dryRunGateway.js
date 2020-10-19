import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const dryRunGateway = {
    namespaced: true,
    state() {
        return {
            name: 'Dry Run Test Gateway'
        };
    },
    mutations: {},
    getters: {

    },
    actions: {

    }
};

export default dryRunGateway;

