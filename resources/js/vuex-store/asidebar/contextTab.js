import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);


const contextTab = {
    namespaced: true,
    state() {
        return {
            activeTab: 'empty-context-tab',
            title: 'Context Sensitive Menu'
        };
    },
    mutations: {
        setActiveTab(state, comp) {
            state.activeTab = comp;
        },
        setTitle(state, title) {
            state.title = title;
        }
    },
    getters: {

    },
    actions: {

    }
};

export default contextTab;
