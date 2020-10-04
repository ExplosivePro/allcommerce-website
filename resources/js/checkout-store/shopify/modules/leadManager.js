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
        /*

        leadEmail(state, email) {
            console.log('Committing leadEmail to '+email);
            state.leadEmail = email;
        },
        shippingValidated(state, flag) {
            console.log('Mutating shippingValidated to '+ flag);
            state.shippingValidated = flag;
        },
        billingValidated(state, flag) {
            console.log('Mutating billingValidated to '+ flag);
            state.billingValidated = flag;
        },
        loading(state, flag) {
            console.log('Mutating loading to '+ flag);
            state.loading = flag;
        },
        apiUrl(state, url) {
            console.log('Mutating apiUrl to '+ url);
            state.apiUrl = url;
        },
        shippingAddress(state, {col, val}) {
            console.log(`Committing shippingAddress.${col} to ${val}`);

            state.shippingAddress[col] = val;
        },
        billingAddress(state, {col, val}) {
            console.log(`Committing billingAddress.${col} to ${val}`);

            state.billingAddress[col] = val;
        },
        billingShippingSame(state, flag) {
            console.log('Mutating billingShippingSame to '+ flag);
            state.billingShippingSame = flag;
        },
        shippingLine(state, val) {
            console.log('Committing shippingLine to ',val);
            state.shippingLine = val;
        },
        shippingReady(state, flag) {
            console.log('Mutating shippingReady to '+ flag);
            state.shippingReady = flag;
        },
        showShipping(state, flag) {
            console.log('Mutating showShipping to '+ flag);
            state.showShipping = flag;
        },
        taxLine(state, val) {
            console.log('Committing taxLine to ',val);
            state.taxLine = val;
        }
        */
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

                                    /* @todo - get ready to uncomment this!
                                    if('shipping_uuid' in data) {
                                        context.commit('shippingAddressUuid', data['shipping_uuid']);
                                        console.log('Calling getShippingAndTax -')
                                        context.commit('shippingReady', true);
                                        context.dispatch('getShippingAndTax', payload);
                                    }

                                    if('billing_uuid' in data) {
                                        context.commit('billingAddressUuid', data['billing_uuid']);
                                    }
                                    */
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
        /* Billing
        validateBillingAddress(context) {
            if(context.state.billingShippingSame) {
                context.commit('billingValidated', false);
                console.log('Cannot validate billing if shipping is the same. If shipping is good, so is billing. Right? #mathz');
            }
            else {
                context.commit('billingValidated', false);
                if(('billingFirst' in context.state.billingAddress) && (context.state.billingAddress.billingFirst !== '')) {
                    if(('billingLast' in context.state.billingAddress) && (context.state.billingAddress.billingLast !== '')) {
                        if(('billingAddress' in context.state.billingAddress) && (context.state.billingAddress.billingAddress !== '')) {
                            if(('billingCity' in context.state.billingAddress) && (context.state.billingAddress.billingCity !== '')) {
                                if(('billingCountry' in context.state.billingAddress) && (context.state.billingAddress.billingCountry !== '')) {
                                    if(('billingState' in context.state.billingAddress) && (context.state.billingAddress.billingState !== '')) {
                                        if(('billingZip' in context.state.billingAddress) && (context.state.billingAddress.billingZip !== '')) {
                                            if(('billingPhone' in context.state.billingAddress) && (context.state.billingAddress.billingPhone !== '')) {
                                                context.commit('billingValidated', true);
                                                console.log('billingAddress validated!');
                                            }
                                            else {
                                                context.commit('billingValidated', false);
                                                console.log('billingAddress not validated');
                                            }
                                        }
                                        else {
                                            context.commit('billingValidated', false);
                                            console.log('billingAddress not validated');
                                        }
                                    }
                                    else {
                                        context.commit('billingValidated', false);
                                        console.log('billingAddress not validated');
                                    }
                                }
                                else {
                                    context.commit('billingValidated', false);
                                    console.log('billingAddress not validated');
                                }
                            }
                            else {
                                context.commit('billingValidated', false);
                                console.log('billingAddress not validated');
                            }
                        }
                        else {
                            context.commit('billingValidated', false);
                            console.log('billingAddress not validated');
                        }
                    }
                    else {
                        context.commit('billingValidated', false);
                        console.log('billingAddress not validated');
                    }
                }
                else {
                    context.commit('billingValidated', false);
                    console.log('billingAddress not validated');
                }
            }
        },
        updateBillingAddress(context, {col, val}) {
            console.log(`Mutating billingAddress.${col} to ${val}`);
            context.commit('billingAddress', {col: col, val:val});

            context.dispatch('validateBillingAddress');
        },
        Shipping */
        /*
        validateShippingAddress(context) {
            context.commit('shippingValidated', false);
            if(('shippingFirst' in context.state.shippingAddress) && (context.state.shippingAddress.shippingFirst !== '')) {
                if(('shippingLast' in context.state.shippingAddress) && (context.state.shippingAddress.shippingLast !== '')) {
                    if(('shippingAddress' in context.state.shippingAddress) && (context.state.shippingAddress.shippingAddress !== '')) {
                        if(('shippingCity' in context.state.shippingAddress) && (context.state.shippingAddress.shippingCity !== '')) {
                            if(('shippingCountry' in context.state.shippingAddress) && (context.state.shippingAddress.shippingCountry !== '')) {
                                if(('shippingState' in context.state.shippingAddress) && (context.state.shippingAddress.shippingState !== '')) {
                                    if(('shippingZip' in context.state.shippingAddress) && (context.state.shippingAddress.shippingZip !== '')) {
                                        if(('shippingPhone' in context.state.shippingAddress) && (context.state.shippingAddress.shippingPhone !== '')) {
                                            context.commit('shippingValidated', true);
                                            console.log('shippingAddress validated!');
                                        }
                                        else {
                                            context.commit('shippingValidated', false);
                                            console.log('shippingAddress not validated');
                                        }
                                    }
                                    else {
                                        context.commit('shippingValidated', false);
                                        console.log('shippingAddress not validated');
                                    }
                                }
                                else {
                                    context.commit('shippingValidated', false);
                                    console.log('shippingAddress not validated');
                                }
                            }
                            else {
                                context.commit('shippingValidated', false);
                                console.log('shippingAddress not validated');
                            }
                        }
                        else {
                            context.commit('shippingValidated', false);
                            console.log('shippingAddress not validated');
                        }
                    }
                    else {
                        context.commit('shippingValidated', false);
                        console.log('shippingAddress not validated');
                    }
                }
                else {
                    context.commit('shippingValidated', false);
                    console.log('shippingAddress not validated');
                }
            }
            else {
                context.commit('shippingValidated', false);
                console.log('shippingAddress not validated');
            }

        },
        updateShippingAddress(context, {col, val}) {
            console.log(`Mutating shippingAddress.${col} to ${val}`);
            context.commit('shippingAddress', {col: col, val:val});

            context.dispatch('validateShippingAddress');
        },
         Other */
        /*
        setLoading(context, flag) {
            console.log('Committing loading to '+flag);
            context.commit('loading', flag);
        },
        setApiUrl(context, url) {
            console.log('Committing apiUrl to '+url);
            context.commit('apiUrl', url);
        },
        setBillingShippingSame(context, flag) {
            console.log('Committing billingShippingSame to '+flag);
            context.commit('billingShippingSame', flag);
        },
        createOrUpdateLead(context, payload) {
            context.commit('loading', true);

            if(context.state.leadUuid !== '') {
                console.log('Adding leadUuid '+ context.state.leadUuid);
                payload['lead_uuid'] = context.state.leadUuid;
            }

            if(context.state.billingAddressUuid !== '') {
                console.log('Adding billingAddressUuid '+ context.state.billingAddressUuid);
                payload['billing_uuid'] = context.state.billingAddressUuid;
            }

            if(context.state.shippingAddressUuid !== '') {
                console.log('Adding shippingAddressUuid '+ context.state.shippingAddressUuid);
                payload['shipping_uuid'] = context.state.shippingAddressUuid;
            }

            console.log('Transmitting lead... ',payload);

            if(payload.reference === 'email') {
                context.commit('leadEmail', payload.value);
            }

            let url = `${context.state.apiUrl}/api/checkout/leads/`;
            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('Lead CreateOrUpdate call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                context.commit('leadUuid', data['lead_uuid']);

                                if('shipping_uuid' in data) {
                                    context.commit('shippingAddressUuid', data['shipping_uuid']);
                                    console.log('Calling getShippingAndTax -')
                                    context.commit('shippingReady', true);
                                    context.dispatch('getShippingAndTax', payload);
                                }

                                if('billing_uuid' in data) {
                                    context.commit('billingAddressUuid', data['billing_uuid']);
                                }
                            }
                        }
                        else {
                            context.commit('shippingReady', false);
                            context.commit('showShipping', false);
                        }
                    }
                    else {
                        context.commit('shippingReady', false);
                        context.commit('showShipping', false);
                    }

                    context.commit('loading', false);
                })
                .catch(err => {
                    console.log(err);
                    context.commit('loading', false);
                    context.commit('shippingReady', false);
                    context.commit('showShipping', false);
                });
        },
        getShippingAndTax(context,payload) {
            let url = `${context.state.apiUrl}/api/checkout/shipping-tax/`;
            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('getShippingAndTax call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                context.commit('taxLine', data['tax']);
                                context.commit('shippingLine', data['shipping']);

                                if('shipping_uuid' in data) {
                                    context.commit('shippingAddressUuid', data['shipping_uuid']);
                                }

                                if('billing_uuid' in data) {
                                    context.commit('billingAddressUuid', data['billing_uuid']);
                                }
                            }
                            context.commit('shippingReady', true);
                        }
                        else {
                            context.commit('shippingReady', -1);
                            context.commit('showShipping', false);
                        }
                    }

                    context.commit('loading', false);
                })
                .catch(err => {
                    console.log(err);
                    context.commit('shippingReady', -1);
                    context.commit('showShipping', false);
                    context.commit('loading', false);
                });
        }
        */
    }
};

export default leadManager;
