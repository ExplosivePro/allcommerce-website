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

        };
    },
    mutations: {

    },
    getters: {
        tabbedLinksAreLoading(state) {
            return state.tabbedLinks.loading;
        },
        tabbedLinks(state) {
            return state.tabbedLinks.links;
        }
    },
    actions: {
        fetchTabbedLinks({commit, dispatch}) {
            commit('tabbedLinks/feature', 'paymentGateways');
            dispatch('tabbedLinks/fetchLinks');
        }
    }
};

export default paymentGatewaysManager;
