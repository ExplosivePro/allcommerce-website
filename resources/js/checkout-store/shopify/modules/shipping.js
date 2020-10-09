import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const shipping = {
    namespaced: true,
    state() {
        return {
            shipping: 0.00,
            rates: '',
            qualifiedRates: '',
            selectedRate: 0.00,
            moduleReady: false,
            availableMethods: [],
        };
    },
    mutations: {
        shipping(state, price) {
            state.shipping = price;
        },
        rates(state, rates) {
            state.rates = rates;
        },
        qualifiedRates(state, rates) {
            state.qualifiedRates = rates;
        },
        selectedRate(state, rate) {
            console.log('Mutating selectedRate to '+ rate);
            state.selectedRate = rate;
        },
        moduleReady(state, flag) {
            console.log('Mutating moduleReady to '+ flag);
            state.moduleReady = flag;
        },
        availableMethods(state, methods) {
            console.log('Mutating availableMethods with ', methods);
            state.availableMethods = methods;
        }
    },
    getters: {
        moduleReady(state) {
            return state.moduleReady;
        },
        availableMethods(state) {
            return state.availableMethods;
        },
        selectedRate(state) {
            return state.selectedRate;
        }
    },
    actions: {
        updateShippingRates({commit}, rates) {

            //commit the rates data to the state
            commit('rates', rates);

            // @todo - curate qualified rates
            let tempRates = [];
            for(let x in rates.priceBased) {
                tempRates.push(rates.priceBased[x]);
            }

            for(let x in rates.weightBased) {
                tempRates.push(rates.weightBased[x]);
            }

            // Add all the rates to a nice fat object, priceBased first
            commit('qualifiedRates', tempRates);

            // Set selected rate to the first item in the object
            commit('selectedRate', 0);

            // Set the shipping price
            commit('shipping', tempRates[0].price);
        }
    }
};

export default shipping;
