import Vue from 'vue';
import Vuex from 'vuex';

// Module imports go here.
import leadManager from "./shopify/modules/leadManager";
import geography from "./geography";
import shipping from "./shopify/modules/shipping";
import priceCalc from "./shopify/modules/priceCalc";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        leadManager,
        geography,
        priceCalc,
        /*
        shipping,
         */
    },
    state() {
        return {
            devMode:         false,
            oneClickMode:    false,

            emailReady:      false,
            shippingReady:   false,
            billingReady:    false,
            postageReady:    false,
            customerReady:   false,
            draftOrderReady: false,
            paymentReady:    false,

            loading:         true,
            backendUrl:      '',
            shopUuid:        '',
            leadUuid:        '',
            billUuid:        '',
            shipUuid:        '',
            draftOrderUuid:  '',
            customerEmail:   '',
            optInMailing:    true,
            checkoutType:    '',
            checkoutId:      '',
            cart: '',
        };
    },
    mutations: {
        devMode(state, flag) {
            console.log('Mutating devMode to '+flag);
            state.devMode = flag;
        },
        loading(state, flag) {
            console.log('Mutating loading to '+ flag);
            state.loading = flag;
        },
        backendUrl(state, url) {
            console.log('Mutating backendUrl to '+ url);
            state.backendUrl = url;
            state.leadManager.apiUrl = url;
        },
        checkoutType(state, checkoutType) {
            console.log('Mutating checkoutType to '+checkoutType);
            state.checkoutType = checkoutType;
        },
        checkoutId(state, checkoutId) {
            console.log('Mutating checkoutId to '+checkoutId);
            state.checkoutId = checkoutId;
        },
        shopUuid(state, uuid) {
            console.log('Mutating shopUuid to '+uuid);
            state.shopUuid = uuid;
        },
        customerEmail(state, email) {
            console.log('Mutating customerEmail to '+email);
            state.customerEmail = email;

            state.emailReady = (state.customerEmail !== '');
            state.leadManager.emailReady = (state.customerEmail !== '');
        },
        leadUuid(state, uuid) {
            console.log('Mutating leadUuid to '+uuid);
            state.leadUuid = uuid;
            state.leadManager.leadUuid = uuid;
        },
        optInMailing(state, flag) {
            console.log('Mutating optInMailing to '+ flag);
            state.optInMailing = flag;

            // @todo - send to whatever other modules need it.
        },
        cart(state, cart) {
            console.log('Mutating cart to ',cart);
            state.cart = cart;
        },
    },
    getters: {
        loading(state) {
            return state.loading;
        },
        customerEmail(state) {
            return state.customerEmail;
        },
        itemPrices(state) {
            return state.priceCalc.itemPrices;
        }
        /*
        shippingAmt({shipping}) {
            return shipping.shipping;
        },
        getSubTotal({priceCalc}) {
            return priceCalc.subtotal;
        },
        getTotal({priceCalc}) {
            return priceCalc.total;
        }
         */
    },
    actions: {
        setLoading(context, flag) {
            console.log('Committing loading to '+flag);
            context.commit('loading', flag);
        },
        configCheckout(context, data) {
            console.log('Committing checkoutType to '+data.type);
            context.commit('checkoutType', data.type);

            console.log('Committing checkoutId to '+data.id);
            context.commit('checkoutId', data.id);
        },
        updateLeadEmail(context) {
            // Curate the payload
            let payload = {
                email: context.state.customerEmail,
                checkoutType: context.state.checkoutType,
                checkoutId: context.state.checkoutId,
                shopUuid: context.state.shopUuid,
                emailList: context.state.optInMailing
            };

            // Account for the leadUuid and send to update in leadManager
            if(context.state.leadUuid !== '')
            {
                payload['lead_uuid'] = context.state.leadUuid;
                context.dispatch('leadManager/updateLeadEmail', payload);
            }
            else {
                // Send to create in leadManager
                context.dispatch('leadManager/createNewLeadWithEmail', payload);
            }
        },
        setShippingReady(context, flag) {
            context.dispatch('leadManager/setShippingReady', flag);

            if(flag) {
                if(context.state.leadUuid === '') {
                    let payload = {
                        checkoutType: context.state.checkoutType,
                        checkoutId: context.state.checkoutId,
                        shopUuid: context.state.shopUuid,
                        emailList: context.state.optInMailing
                    };

                    context.dispatch('leadManager/createNewLeadWithShipping', payload);
                }
                else {
                    let payload = {
                        'lead_uuid': context.state.leadUuid,
                        checkoutType: context.state.checkoutType,
                        checkoutId: context.state.checkoutId,
                        shopUuid: context.state.shopUuid,
                        emailList: context.state.optInMailing
                    };

                    context.dispatch('leadManager/updateLeadShipping', payload);
                }
            }
        },
        setBillingReady(context, flag) {
            context.dispatch('leadManager/setBillingReady', flag);

            if(flag) {
                let payload = {
                    'lead_uuid': context.state.leadUuid,
                    checkoutType: context.state.checkoutType,
                    checkoutId: context.state.checkoutId,
                    shopUuid: context.state.shopUuid,
                    emailList: context.state.optInMailing
                };

                context.dispatch('leadManager/updateLeadBilling', payload);
            }
        },
        initCart(context, cart) {
            console.log('Committing cart to ',cart);
            context.commit('cart', cart);
            context.dispatch('priceCalc/initCart', cart);
        },
    }
});
