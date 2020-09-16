import Vue from 'vue';
import Vuex from 'vuex';

// Module imports go here.
import leadManager from "./shopify/modules/leadManager";
import geography from "./geography";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        leadManager,
        geography
    },
    state() {
        return {
            loading: true,
            checkoutType: '',
            checkoutId: '',
            shopUuid: '',
            emailList: true,
            email: '',
            cart: '',
            leadUuid: '',
        };
    },
    mutations: {
        shopUuid(state, uuid) {
            console.log('Mutating shopUuid to '+uuid);
            state.shopUuid = uuid;
        },
        leadUuid(state, uuid) {
            console.log('Mutating leadUuid to '+uuid);
            state.leadUuid = uuid;
        },
        cart(state, cart) {
            console.log('Mutating cart to ',cart);
            state.cart = cart;
        },
        email(state, email) {
            console.log('Mutating email to '+email);
            state.email = email;
        },
        emailList(state, flag) {
            console.log('Mutating emailList to '+flag);
            state.emailList = flag;
        },
        checkoutType(state, checkoutType) {
            console.log('Mutating checkoutType to '+checkoutType);
            state.checkoutType = checkoutType;
        },
        checkoutId(state, checkoutId) {
            console.log('Mutating checkoutId to '+checkoutId);
            state.checkoutId = checkoutId;
        },
        loading(state, flag) {
            console.log('Mutating loading to '+ flag);
            state.loading = flag;
        }
    },
    getters: {

    },
    actions: {
        setLoading(context, flag) {
            console.log('Committing loading to '+flag);
            context.commit('loading', flag);
        },
        setShopUuid(context, uuid) {
            console.log('Committing shopUuid to '+uuid);
            context.commit('shopUuid', uuid);

            // @todo - ping out for the payment gateway among other thangs
        },
        setLeadUuid(context, uuid) {
            console.log('Committing leadUuid to '+uuid);
            context.commit('leadUuid', uuid);
        },
        initCart(context, cart) {
            console.log('Committing cart to ',cart);
            context.commit('cart', cart);
        },
        configCheckout(context, data) {
            console.log('Committing checkoutType to '+data.type);
            context.commit('checkoutType', data.type);

            console.log('Committing checkoutId to '+data.id);
            context.commit('checkoutId', data.id);
        },
        updateBillingShipping(context) {
            console.log('is shipping validated? '+context.state.leadManager.shippingValidated);
            if(context.state.leadManager.shippingValidated && (!context.state.leadManager.loading)) {
                let billship = {
                    shipping: context.state.leadManager.shippingAddress,
                    billing: context.state.leadManager.billingAddress
                };
                console.log('updating billing & shipping to '+billship);

                let payload = {
                    reference: 'shipping',
                    value: billship,
                    checkoutType: context.state.checkoutType,
                    checkoutId: context.state.checkoutId,
                    shopUuid: context.state.shopUuid,
                    emailList: context.state.emailList
                };

                context.dispatch('leadManager/createOrUpdateLead', payload);
            }
            else {
                console.log('not updating billing & shipping');
            }

        },
        updateEmail(context, email) {
            console.log('Committing email to '+email);
            context.commit('email', email);

            let payload = {
                reference: 'email',
                value: context.state.email,
                checkoutType: context.state.checkoutType,
                checkoutId: context.state.checkoutId,
                shopUuid: context.state.shopUuid,
                emailList: context.state.emailList
            };

            context.dispatch('leadManager/createOrUpdateLead', payload);
        },
        updateEmailList(context, flag) {
            console.log('Updating emailList via email to '+ flag);
            context.commit('emailList', flag);

            let payload = {
                reference: 'email',
                value: context.state.email,
                checkoutType: context.state.checkoutType,
                checkoutId: context.state.checkoutId,
                shopUuid: context.state.shopUuid,
                emailList: flag
            };

            context.dispatch('leadManager/createOrUpdateLead', payload);
        }
    }
});
