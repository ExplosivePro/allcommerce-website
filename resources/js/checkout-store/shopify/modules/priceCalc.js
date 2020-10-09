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
            taxLines: []
        };
    },
    mutations: {
        total(state, price) {
            console.log('Committing new total -', price);
            state.total = price;
        },
        shipping(state, price) {
            console.log('Committing new shipping -', price);
            state.shipping = price;

            console.log('You should see a new price of '+ (state.subtotal + state.tax + state.shipping))
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
        },
        addToItemPrices(state, priceObj) {
            console.log('Committing insert to itemPrices -', priceObj);
            state.itemPrices.push(priceObj);
        }
    },
    getters: {
        getItemPrices(state) {
            return state.itemPrices;
        },
        getSubTotal(state) {
            return state.subtotal;
        },
        getTotal(state) {
            let a = parseFloat(parseFloat(state.subtotal) + parseFloat(state.tax));
            let b = parseFloat(state.shipping);

            return (a + b);
        },
        getTaxLines(state) {
            return state.taxLines;
        }
    },
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
        initCart(context, cart) {
            console.log('priceCal initCart, setting up car!', cart);
            let curated = [];
            for(let x in cart) {
                let priceRow = {
                    qty: cart[x].qty,
                    price: cart[x].variant['price'],
                    variant: cart[x].variant,
                    image: cart[x].image,
                    item: cart[x].item
                };

                context.commit('addToItemPrices', priceRow);
                curated.push(priceRow);
            }

            context.dispatch('calculateSubTotal', curated);
        },
        updateTax(context, tax) {

        }
    }
};

export default priceCalc;
