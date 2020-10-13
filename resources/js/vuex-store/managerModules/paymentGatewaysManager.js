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
            clientContentView: 'providers'
        };
    },
    mutations: {
        clientContentView(state, view) {
            console.log('Mutating clientContentView to ' + view);
            state.clientContentView = view;
        }
    },
    getters: {
        tabbedLinksAreLoading(state) {
            return state.tabbedLinks.loading;
        },
        tabbedLinks(state) {
            return state.tabbedLinks.links;
        },
        getClientContentView(state) {
            // @todo - do some validation so it returns a standard error if applicable
            return state.clientContentView;
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
