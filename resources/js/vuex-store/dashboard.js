import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import widgets from './widgets';

const dashboard = {
    namespaced: true,
    modules: {
        widgets
    },
    state() {
        return {
            count: 2,
        };
    },
    mutations: {

    },
    getters: {
        widgets (state, getters) {
            return state.widgets.widgets;
        },
        widgetsErrorMsg (state, getters) {
            return state.widgets.widgetsErrorMsg;
        },
        rightWidgetSwap (state, getters) {
            return state.widgets.rightWidgetSwap;
        },
    },
    actions: {
        getUserAvailableWidgets(context, args) {
            console.log('getUserAvailableWidgets context', context);

            context.dispatch('getAvailableWidgets', {type: 'dashboard'});
        },
        somethingDumb() {
            alert('OMG OMG OMG');
            return 'undefined';
        }
    }
};

export default dashboard;
