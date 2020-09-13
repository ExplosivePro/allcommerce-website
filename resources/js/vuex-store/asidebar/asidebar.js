import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import contextTab from "./contextTab";

const asidebar = {
    namespaced: true,
    modules: {
        contextTab
    },
    state() {
        return {

        };
    },
    mutations: {

    },
    getters: {
        getActiveContextTabComponent(state, getters) {
            return state.contextTab.activeTab;
        }
    },
    actions: {
        setContextTabActiveComponent(context, tab) {
            console.log('setContentTabActiveComponent context', context);
            console.log('setContentTabActiveComponent tab', tab);

            context.commit('contextTab/setActiveTab', tab);
        },
    }
};

export default asidebar;
