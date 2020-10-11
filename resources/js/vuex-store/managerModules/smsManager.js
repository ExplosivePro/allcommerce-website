import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import tabbedLinks from "./tabbedLinks";

const smsManager = {
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
            commit('tabbedLinks/feature', 'sms');
            dispatch('tabbedLinks/fetchLinks');
        }
    }
};

export default smsManager;
