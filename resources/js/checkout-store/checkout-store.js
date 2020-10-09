import Vue from 'vue';
import Vuex from 'vuex';

// Module imports go here.
import leadManager from "./shopify/modules/leadManager";
import geography from "./geography";
import shipping from "./shopify/modules/shipping";
import priceCalc from "./shopify/modules/priceCalc";
import oneClickManager from "./shopify/modules/oneClickManager";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        oneClickManager,
        leadManager,
        geography,
        priceCalc,
        shipping,
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
            taxReady:        false,
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
            state.oneClickManager.apiUrl = url;
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
        draftOrderReady(state, flag) {
            console.log('Mutating draftOrderReady to '+ flag);
            state.draftOrderReady = flag;
            state.leadManager.draftOrderReady = flag;

        },
        taxReady(state, flag) {
            console.log('Mutating taxReady to '+ flag);
            state.taxReady = flag;
            state.leadManager.taxReady = flag;

        },
        postageReady(state, flag) {
            console.log('Mutating postageReady to '+ flag);
            state.postageReady = flag;

        },
        cart(state, cart) {
            console.log('Mutating cart to ',cart);
            state.cart = cart;
        },
        setTaxInPriceCalc(state, tax) {
            console.log('setTaxInPriceCalc mutating -', tax);

            state.priceCalc.tax = tax.totalTax;
            state.priceCalc.taxLines = tax.lines;
        }
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
        },
        taxReady(state) {
            return state.taxReady;
        },
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
        setPostageReady(context, flag) {
            console.log('Committing postageReady to '+flag);
            context.commit('postageReady', flag);
            context.commit('shipping/moduleReady', flag);
        },
        initCart(context, cart) {
            console.log('Committing cart to ',cart);
            context.commit('cart', cart);
            context.dispatch('priceCalc/initCart', cart);
        },
        ajaxDraftOrderFromShippingMethod(context, shippingRate) {

            console.log('Going to create or update the draftOrder! + '+context.state.leadUuid, shippingRate);
            let payload = {
                shopUuid: context.state.shopUuid,
                leadUuid: context.state.leadUuid,
                shippingMethod: shippingRate
            };

            let url = `${context.state.backendUrl}/api/checkout/shopify/leads/create/draft-order/sm`;
            console.log(`Pinging ${url}`, payload);

            // Call the endpoint
            axios.post(url, payload)
                .then(res => {
                    console.log('DraftOrder call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            // On success, update price calc with shipping and tax data
                            if(data['success']) {
                                let tax = {
                                    totalTax: data['total_tax'],
                                    lines: data['tax_lines']
                                }

                                context.commit('setTaxInPriceCalc', tax);
                                context.commit('draftOrderReady', true);
                                context.commit('taxReady', true);
                            }
                        }
                        else {
                            console.log(data['reason']);
                        }
                    }
                    else {
                        console.log('Strange response from server');
                    }
                })
                .catch(err => {
                    console.log(err);
                    context.commit('loading', false);
                    context.commit('emailReady', false);
                });
        }
    }
});
