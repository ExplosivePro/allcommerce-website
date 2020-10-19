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
            creditModule: ''
        };
    },
    mutations: {
        creditModule(state, module) {
            state.creditModule = module;
        }
    },
    getters: {
        creditModule(state) {
            return state.creditModule;
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

            alert(name);

        }
    }
};

export default checkoutGatewayManager;

