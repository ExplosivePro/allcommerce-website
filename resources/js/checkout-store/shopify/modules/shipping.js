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
            selectedRate: ''
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
            state.selectedRate = rate;
        }
    },
    getters: {},
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
