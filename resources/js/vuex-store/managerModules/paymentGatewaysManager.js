import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import tabbedLinks from "./tabbedLinks";

const paymentGatewaysManager = {
    namespaced: true,
    modules: {
        tabbedLinks
    },
    state() {
        return {
            clientContentView: 'providers',
            merchantMode: false,
            merchantShops: [],
            creditProviders: [],
            expressProviders: [],
            installmentProviders: [],
            assignmentStatus: false,
            clientEnabledGateways: false
        };
    },
    mutations: {
        clientContentView(state, view) {
            console.log('Mutating clientContentView to ' + view);
            state.clientContentView = view;
        },
        merchantMode(state, flag) {
            console.log('Mutating merchantMode to ' + flag);
            state.merchantMode = flag;
        },
        merchantShops(state, shops) {
            console.log('Mutating merchantShops to ', shops);
            state.merchantShops = shops;
        },
        creditProviders(state, gateways) {
            console.log('Mutating creditProviders to ', gateways);
            state.creditProviders = gateways;
        },
        expressProviders(state, gateways) {
            console.log('Mutating expressProviders to ', gateways);
            state.expressProviders = gateways;
        },
        installmentProviders(state, gateways) {
            console.log('Mutating installmentProviders to ', gateways);
            state.installmentProviders = gateways;
        },
        assignmentStatus(state, status) {
            console.log('Mutating assignmentStatus to ', status);
            state.assignmentStatus = status;
        },
        clientEnabledGateways(state, gateways) {
            console.log('Mutating clientEnabledGateways with ', gateways);

            let temp = {};
            for(let x in gateways) {
                temp[gateways[x]['payment_provider'].id] = gateways[x];
            }

            state.clientEnabledGateways = temp;
        }
    },
    getters: {
        tabbedLinksAreLoading(state) {
            return state.tabbedLinks.loading;
        },
        tabbedLinks(state) {
            return state.tabbedLinks.links;
        },
        merchantMode(state) {
            return state.merchantMode;
        },
        merchantShops(state) {
            return state.merchantShops;
        },
        getClientContentView(state) {
            // @todo - do some validation so it returns a standard error if applicable
            return state.clientContentView;
        },
        creditProviders(state) {
            return state.creditProviders;
        },
        expressProviders(state) {
            return state.expressProviders;
        },
        installmentProviders(state) {
            return state.installmentProviders;
        },
        getMerchantShopAssignedGateways(state) {
            let results = [];
            if(state.merchantShops.length > 0) {
                for(let x in state.merchantShops) {
                    let shop = state.merchantShops[x];
                    results.push({
                        id: shop.id,
                        name: shop.name,
                        gateways: shop['curated_payment_gateways']
                    });
                }

            }

            return results;
        },
        getClientEnabledGateways(state) {
            let results = {
                credit: {},
                express: {},
                install: {}
            };

            let credit = state.creditProviders;
            console.log('creditProviders -', credit);

            for(let x in credit) {
                if(state.clientEnabledGateways !== false) {
                    if(credit[x].id in state.clientEnabledGateways) {
                        results.credit[credit[x].id] = state.clientEnabledGateways[credit[x].id];
                    }
                }
            }

            return results;
        },
        assignmentStatus(state) {
            return state.assignmentStatus;
        },
    },
    actions: {
        fetchTabbedLinks({commit, dispatch}) {
            commit('tabbedLinks/feature', 'paymentGateways');
            dispatch('tabbedLinks/fetchLinks');
        },
        assignGatewayToShop(context, args) {
            console.log('Assigning a gateway!', args);
            let shop = context.state.merchantShops[args.shop];
            console.log('to Shop ', shop);
            console.log('getClientEnabledGateways ', context.getters.getClientEnabledGateways);

            let payload = {
                'gateway_uuid': args.gateway.id,
                clientGatewayEnabledId: context.getters.getClientEnabledGateways[args.type][args.gateway.id].id,
                type: args.type
            };

            let url = `/access/payment-gateways/${shop.id}/assign`;
            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('Lead assignGatewayToShop call response - ', res);

                    if('data' in res) {
                        let data = res['data'];

                        if('success' in data) {
                            if(data['success']) {
                                context.commit('assignmentStatus', {
                                    error: 'Success! Hang on a Sec'
                                });

                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                            else {
                                context.commit('assignmentStatus', {
                                    error: data['reason']
                                });
                            }
                        }
                        else {
                            context.commit('assignmentStatus', {
                                error: 'Bad Response From Server'
                            });
                        }
                    }
                    else {
                        context.commit('assignmentStatus', {
                            error: 'Unknown Response From Server'
                        });
                    }
                })
                .catch(err => {
                    console.log('Server Error. assignGatewayToShop', err);
                    context.commit('assignmentStatus', {
                        error: 'error'
                    })
                });
        },
        unAssignGatewayToShop(context, args) {
            console.log('UnAssigning a gateway :(', args);
            let shop = context.state.merchantShops[args.shop];
            console.log('to Shop ', shop);
            console.log('getClientEnabledGateways ', context.getters.getClientEnabledGateways);

            let payload = {
                gatewayAssignedId: args.gateway.id,
                clientGatewayEnabledId: context.getters.getClientEnabledGateways[args.type][args.gateway.id].id,
                type: args.type
            };

            let url = `/access/payment-gateways/${shop.id}/unassign`;
            console.log(`Pinging ${url}`, payload);

            axios.delete(url, {
                data: payload
            })
                .then(res => {
                    console.log('Lead assignGatewayToShop call response - ', res);

                    if('data' in res) {
                        let data = res['data'];

                        if('success' in data) {
                            if(data['success']) {
                                context.commit('assignmentStatus', {
                                    error: 'Success! Hang on a Sec'
                                });

                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                            else {
                                context.commit('assignmentStatus', {
                                    error: data['reason']
                                });
                            }
                        }
                        else {
                            context.commit('assignmentStatus', {
                                error: 'Bad Response From Server'
                            });
                        }
                    }
                    else {
                        context.commit('assignmentStatus', {
                            error: 'Unknown Response From Server'
                        });
                    }
                })
                .catch(err => {
                    console.log('Server Error. assignGatewayToShop', err);
                    context.commit('assignmentStatus', {
                        error: 'error'
                    })
                });
        }
    }
};

export default paymentGatewaysManager;
