import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const priceCalc = {
    namespaced: true,
    state() {
        return {
            shipping: 0.00,
            tax: 0.00,
            subtotal: 0.00,
            total: 0.00,
            itemPrices: [],
        };
    },
    mutations: {
        total(state, price) {
            console.log('Committing new total -', price);
            state.total = price;
        },
        subTotal(state, price) {
            console.log('Committing new subtotal -', price);
            state.subtotal = price;
        },
        tax(state, tax) {
            console.log('Committing new tax -', tax);
            state.tax = tax;
        },
        itemPrices(state, priceObj) {
            console.log('Committing new itemPrices -', priceObj);
            state.itemPrices = priceObj;
        }
    },
    getters: {},
    actions: {
        itemPrices({commit, dispatch}, priceObj) {
            commit('itemPrices', priceObj);
            dispatch('calculateSubTotal', priceObj);
        },
        calculateTotal(context) {
            let price = 0.00;

            let sub = context.state.subtotal;
            let shipping = context.state.shipping;
            let tax = context.state.tax;

            price = parseFloat(parseFloat(price)
                + parseFloat(sub)
                + parseFloat(shipping)
                + parseFloat(tax));

            console.log('calculateTotal -', [price]);

            context.commit('total', price);
        },
        calculateSubTotal(context, priceObj) {
            let price = 0.00;
            for(let x in priceObj) {
                let sub = priceObj[x].price * priceObj[x].qty;
                price = parseFloat(parseFloat(price) + parseFloat(sub));
            }

            context.commit('subTotal', price);
            context.dispatch('calculateTotal');
        },
    }
};

export default priceCalc;
