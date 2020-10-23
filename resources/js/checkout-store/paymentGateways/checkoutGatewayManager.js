import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import dryRunGateway from "./creditGateways/dryRunGateway";
import stripeGateway from "./creditGateways/stripeGateway";
import brainTreeGateway from "./creditGateways/brainTreeGateway";
import amazonPayments from "./expressGateways/amazonPayments";
import payPalExpress from "./expressGateways/payPalExpress";
import afterPayGateway from "./installPayGateways/afterPayGateway";
import sezzleGateway from "./installPayGateways/sezzleGateway";

const checkoutGatewayManager = {
    namespaced: true,
    modules: {
        dryRunGateway,
        stripeGateway,
        brainTreeGateway,
        amazonPayments,
        payPalExpress,
        afterPayGateway,
        sezzleGateway,
    },
    state() {
        return {
            creditModule: '',
            leadUuid: '',
            loading: '',
            apiUrl: '',
            price: 0
        };
    },
    mutations: {
        creditModule(state, module) {
            state.creditModule = module;
        },
        leadUuid(state, uuid) {
            state.uuid = uuid;
        },
        loading(state, flag) {
            state.loading = flag;
        },
        apiUrl(state, url) {
            state.apiUrl = url;
        },
        price(state, amt) {
            state.price = amt;
        }
    },
    getters: {
        creditModule(state) {
            return state.creditModule;
        },
        loading(state) {
            return state.loading;
        },
        apiUrl(state) {
            return state.apiUrl;
        },
        price(state) {
            return state.price;
        }
    },
    actions: {
        initCreditGateway(context, gateway) {
            console.log('initCreditGateway - ', gateway);
            context.commit('creditModule', gateway.module);
            let module = gateway.module;
        },
        executeCreditAuth(context, payload) {
            console.log('Executing Credit Card Auth', payload);
            let name = context.state[context.getters.creditModule].name;

            if(context.state.leadUuid !== '') {
                // @todo - this is where the magic happens
                context.commit('loading', true);
                context.commit(context.getters.creditModule+'/apiUrl', context.getters.apiUrl);
                payload.details['leadUuid'] = context.state.leadUuid;
                context.dispatch(context.getters.creditModule+'/auth', payload.details);
            }
            else {
                alert('Finish filling out the form, first!');
                //alert(name);
            }

        }
    }
};

export default checkoutGatewayManager;

