import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const leadManager = {
    namespaced: true,
    state() {
        return {
            loading: false,
            apiUrl: '',
            leadUuid: '',
            leadEmail: '',
            emailReady:      false,
            shippingReady:   false,
            billingReady:    false,
            draftOrderReady: false,
            taxReady:        false,
            oneClickReady: false,
            oneClickData: '',
            shippingAddress: {},
            billingAddress: {},
            billingAddressUuid: '',
            shippingAddressUuid: '',
        };
    },
    mutations: {
        loading(state, flag) {
            console.log('Mutating loading to '+ flag);
            state.loading = flag;
        },
        leadUuid(state, uuid) {
            console.log('Committing leadUuid to '+uuid);
            state.leadUuid = uuid;
        },
        emailReady(state, flag) {
            console.log('Mutating emailReady to '+ flag);
            state.emailReady = flag;
        },
        shippingReady(state, flag) {
            console.log('Mutating shippingReady to '+ flag);
            state.shippingReady = flag;
        },
        billingReady(state, flag) {
            console.log('Mutating billingReady to '+ flag);
            state.billingReady = flag;
        },
        oneClickReady(state, flag) {
            console.log('Mutating oneClickReady to '+ flag);
            state.oneClickReady = flag;
        },
        oneClickData(state, data) {
            console.log('Mutating oneClickData to ', data);
            state.oneClickData = data;
        },
        shippingAddress(state, args) {
            console.log(`Mutating ${args.method} in shippingAddress to `+ args.value);
            state.shippingAddress[args.method] = args.value;
        },
        billingAddress(state, args) {
            console.log(`Mutating ${args.method} in billingAddress to `+ args.value);
            state.billingAddress[args.method] = args.value;
        },
        shippingAddressUuid(state, uuid) {
            console.log('Committing shippingAddressUuid to '+uuid);
            state.shippingAddressUuid = uuid;
        },
        billingAddressUuid(state, uuid) {
            console.log('Committing billingAddressUuid to '+uuid);
            state.billingAddressUuid = uuid;
        },
    },
    getters: {
        loading(state) {
            return state.loading;
        },
        leadUuid(state) {
            return state.leadUuid;
        },
        shippingReady(state) {
            return state.shippingReady;
        },
        billingReady(state) {
            return state.billingReady;
        },
        emailReady(state) {
            return state.emailReady;
        },
        oneClickReady(state) {
            return state.oneClickReady;
        },
        oneClickData(state) {
            return state.oneClickData;
        },
        isDraftEligible(state) {
            let results = state.draftOrderReady;

            if(!results
                && (state.leadUuid !== '')
                && (state.billingAddressUuid !== '')
                && (state.shippingAddressUuid !== '')
                && (state.billingReady)
                && (state.shippingReady)
            ) {
                results = true;
            }
        }
    },
    actions: {
        setLoading(context, flag) {
            console.log('Committing loading to '+flag);
            context.commit('loading', flag);
        },
        setShippingMethods(context, data) {

        },
        createNewLeadWithEmail(context, payload) {
            context.commit('loading', true);

            if('lead_uuid' in payload) {
                console.log('Theres a lead UUID in the payload! Dispatching update instead..');
                context.dispatch('updateLeadEmail', payload);
            }
            else {
                let url = `${context.state.apiUrl}/api/checkout/shopify/leads/create/email`;
                console.log(`Pinging ${url}`, payload);

                axios.post(url, payload)
                    .then(res => {
                        console.log('Lead createNewLeadWithEmail call response - ', res);

                        if('data' in res) {
                            let data = res.data;

                            if('success' in data) {
                                if(data['success']) {
                                    context.commit('leadUuid', data['lead_uuid']);
                                    context.commit('emailReady', true);

                                    if('one_click' in data) {
                                        context.commit('oneClickReady', true);
                                        context.commit('oneClickData', data['one_click']);
                                    }
                                }
                            }
                            else {
                                context.commit('emailReady', false);
                            }
                        }
                        else {
                            context.commit('emailReady', false);
                        }

                        context.commit('loading', false);
                    })
                    .catch(err => {
                        console.log(err);
                        context.commit('loading', false);
                        context.commit('emailReady', false);
                    });
            }
        },
        createNewLeadWithShipping(context, payload) {
            context.commit('loading', true);

            payload.shipping = context.state.shippingAddress;

            if(context.state.billingAddress !== {}) {
                payload.billing = context.state.billingAddress;
            }

            let url = `${context.state.apiUrl}/api/checkout/shopify/leads/create/shipping`;
            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('Lead createNewLeadWithShipping call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                context.commit('leadUuid', data['lead_uuid']);

                                if('shipping_uuid' in data) {
                                    context.commit('shippingAddressUuid', data['shipping_uuid']);
                                    context.commit('shippingReady', true);

                                    //console.log('Calling getShippingAndTax -')
                                    //context.dispatch('getShippingAndTax', payload);
                                }

                                if('billing_uuid' in data) {
                                    context.commit('billingAddressUuid', data['billing_uuid']);
                                    context.commit('billingReady', true);
                                }
                            }
                        }
                        else {
                            context.commit('emailReady', false);
                        }
                    }
                    else {
                        context.commit('emailReady', false);
                    }

                    context.commit('loading', false);
                })
                .catch(err => {
                    console.log(err);
                    context.commit('loading', false);
                    context.commit('emailReady', false);
                });
        },
        updateLeadEmail(context, payload) {
            context.commit('loading', true);

            if(context.state.emailReady) {
                if(('lead_uuid' in payload)) {
                    console.log('Dispatching update EMAIL in lead.');

                    let url = `${context.state.apiUrl}/api/checkout/shopify/leads/update/email`;
                    console.log(`Pinging ${url}`, payload);

                    axios.put(url, payload, {headers: {Accept: 'application/json'}})
                        .then(res => {
                            console.log('Lead updateLeadEmail call response - ', res);

                            if('data' in res) {
                                let data = res.data;

                                if('success' in data) {
                                    if(data['success']) {
                                        context.commit('leadUuid', data['lead_uuid']);
                                        context.commit('emailReady', true);

                                        if(context.getters.isDraftEligible) {
                                            if(context.state.draftOrderReady) {
                                                context.dispatch('updateDraftOrder', {})
                                            }
                                            else {
                                                context.dispatch('createNewDraftOrder', {})
                                            }

                                        }
                                    }
                                }
                                else {
                                    context.commit('emailReady', false);
                                    // @todo - investigate getting the leadUUID removed if things made it here.
                                }
                            }
                            else {
                                context.commit('emailReady', false);
                            }
                        })
                        .catch(err => {
                            console.log(err);
                            context.commit('loading', false);
                            context.commit('emailReady', false);
                            // @todo - investigate getting the leadUUID removed if things made it here.
                        });
                }
                else {
                    console.log('No UUID found in the update payload. Skipping...');
                }
            }
            else {
                // @todo - investigate getting the leadUUID removed if things made it here.
                console.log('emailReady is false. Skipping...');
            }
        },
        updateLeadShipping(context, payload) {
            context.commit('loading', true);

            payload.shipping = context.state.shippingAddress;

            if(context.state.shippingAddressUuid !== '') {
                payload['shipping_uuid'] = context.state.shippingAddressUuid;
            }

            if(context.state.billingAddress !== {}) {
                payload.billing = context.state.billingAddress;

                if(context.state.billingAddressUuid !== '') {
                    payload['billing_uuid'] = context.state.billingAddressUuid;
                }
            }

            let url = `${context.state.apiUrl}/api/checkout/shopify/leads/update/shipping`;
            console.log(`Pinging ${url}`, payload);

            axios.put(url, payload)
                .then(res => {
                    console.log('Lead createNewLeadWithShipping call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                context.commit('leadUuid', data['lead_uuid']);

                                if('shipping_uuid' in data) {
                                    context.commit('shippingAddressUuid', data['shipping_uuid']);
                                    context.commit('shippingReady', true);

                                    //console.log('Calling getShippingAndTax -')
                                    //context.dispatch('getShippingAndTax', payload);

                                    if(context.getters.isDraftEligible) {
                                        if(context.state.draftOrderReady) {
                                            context.dispatch('updateDraftOrder', {})
                                        }
                                        else {
                                            context.dispatch('createNewDraftOrder', {})
                                        }

                                    }
                                }

                                if('billing_uuid' in data) {
                                    context.commit('billingAddressUuid', data['billing_uuid']);
                                    context.commit('billingReady', true);
                                }
                            }
                        }
                        else {
                            context.commit('emailReady', false);
                        }
                    }
                    else {
                        context.commit('emailReady', false);
                    }

                    context.commit('loading', false);
                })
                .catch(err => {
                    console.log(err);
                    context.commit('loading', false);
                    context.commit('emailReady', false);
                });
        },
        updateLeadBilling(context, payload) {
            if(context.state.billingAddressUuid !== '') {
                context.commit('loading', true);

                payload.billing = context.state.billingAddress;
                payload['billing_uuid'] = context.state.billingAddressUuid;

                let url = `${context.state.apiUrl}/api/checkout/shopify/leads/update/billing`;
                console.log(`Pinging ${url}`, payload);

                axios.put(url, payload)
                    .then(res => {
                        console.log('Lead createNewLeadWithBilling call response - ', res);

                        if('data' in res) {
                            let data = res.data;

                            if('success' in data) {
                                if(data['success']) {
                                    context.commit('leadUuid', data['lead_uuid']);

                                    if('billing_uuid' in data) {
                                        context.commit('billingAddressUuid', data['billing_uuid']);
                                        context.commit('billingReady', true);

                                        if(context.getters.isDraftEligible) {
                                            if(context.state.draftOrderReady) {
                                                context.dispatch('updateDraftOrder', {})
                                            }
                                            else {
                                                context.dispatch('createNewDraftOrder', {})
                                            }

                                        }
                                    }
                                }
                            }
                            else {
                                context.commit('emailReady', false);
                            }
                        }
                        else {
                            context.commit('emailReady', false);
                        }

                        context.commit('loading', false);
                    })
                    .catch(err => {
                        console.log(err);
                        context.commit('loading', false);
                        context.commit('emailReady', false);
                    });
            }
            else {
                console.log('No billing UUID set, skipping...')
            }
        },
        setShippingReady(context, flag) {
            console.log('Committing shippingReady to '+flag);
            context.commit('shippingReady', flag);
        },
        setBillingReady(context, flag) {
            console.log('Committing billingReady to '+flag);
            context.commit('billingReady', flag);
        },
        updateShippingAddress(context, payload) {
            context.commit('shippingAddress', payload.value)
        },
        updateBillingAddress(context, payload) {
            context.commit('billingAddress', payload.value)
        },
        createNewDraftOrder(context, payload) {
            console.log('createNewDraftOrder')
            context.dispatch('loading', true);
        },
        updateDraftOrder(context, payload) {
            console.log('updateDraftOrder')
            context.dispatch('loading', true);
        },
    }
};

export default leadManager;
