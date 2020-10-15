import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import tabbedLinks from "./tabbedLinks";

const paymentGatewaysManager = {
    namespaced: true,
    modules: {
        tabbedLinks
    },
    state() {
        return {
            clientContentView: 'providers',
            merchantMode: false,
            merchantShops: [],
            creditProviders: [],
            expressProviders: [],
            installmentProviders: [],
        };
    },
    mutations: {
        clientContentView(state, view) {
            console.log('Mutating clientContentView to ' + view);
            state.clientContentView = view;
        },
        merchantMode(state, flag) {
            console.log('Mutating merchantMode to ' + flag);
            state.merchantMode = flag;
        },
        merchantShops(state, shops) {
            console.log('Mutating merchantShops to ' + shops);
            state.merchantShops = shops;
        },
        creditProviders(state, gateways) {
            console.log('Mutating creditProviders to ', gateways);
            state.creditProviders = gateways;
        },
        expressProviders(state, gateways) {
            console.log('Mutating expressProviders to ', gateways);
            state.expressProviders = gateways;
        },
        installmentProviders(state, gateways) {
            console.log('Mutating installmentProviders to ', gateways);
            state.installmentProviders = gateways;
        },
    },
    getters: {
        tabbedLinksAreLoading(state) {
            return state.tabbedLinks.loading;
        },
        tabbedLinks(state) {
            return state.tabbedLinks.links;
        },
        merchantMode(state) {
            return state.merchantMode;
        },
        merchantShops(state) {
            return state.merchantShops;
        },
        getClientContentView(state) {
            // @todo - do some validation so it returns a standard error if applicable
            return state.clientContentView;
        },
        creditProviders(state) {
            return state.creditProviders;
        },
        expressProviders(state) {
            return state.expressProviders;
        },
        installmentProviders(state) {
            return state.installmentProviders;
        },
    },
    actions: {
        fetchTabbedLinks({commit, dispatch}) {
            commit('tabbedLinks/feature', 'paymentGateways');
            dispatch('tabbedLinks/fetchLinks');
        },
    }
};

export default paymentGatewaysManager;
