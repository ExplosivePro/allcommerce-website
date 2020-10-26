import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const stripeGateway = {
    namespaced: true,
    state() {
        return {
            name: 'Stripe',
            apiUrl: '',
        };
    },
    mutations: {
        apiUrl(state, url) {
            state.apiUrl = url;
        },
    },
    getters: {
        apiUrl(state) {
            return state.apiUrl;
        },
    },
    actions: {
        auth(context, payload) {
            console.log('delete this ', payload);

            let url = `${context.state.apiUrl}/api/checkout/payment/credit/auth`;
            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('Stripe Gateway auth call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                context.rootState.checkoutGatewayManager.authTransactionUuid = data['transaction'];
                                context.rootState.checkoutGatewayManager.loading = false;
                            }
                            else {
                                alert(data['reason']);
                                context.rootState.checkoutGatewayManager.loading = false;
                            }
                        }
                        else {
                            alert('Could not connect to server. Try Again');
                            context.rootState.checkoutGatewayManager.loading = false;
                        }
                    }
                    else {
                        alert('Could not reach to server. Try Again');
                        context.rootState.checkoutGatewayManager.loading = false;
                    }

                })
                .catch(err => {
                    console.log(err);
                    alert('Could not connect to server. Try Again');
                    context.rootState.checkoutGatewayManager.loading = false;
                });
        },
        capture(context, authTransactionUuid) {
            let payload = {
                transactionId: authTransactionUuid
            };

            let url = `${context.state.apiUrl}/api/checkout/payment/credit/capture`;
            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('Stripe Gateway capture call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                window.location.href = data['success_url'];
                            }
                            else {
                                alert(data['reason']);
                                context.rootState.checkoutGatewayManager.loading = false;
                            }
                        }
                        else {
                            alert('Could not connect to server. Try Again');
                            context.rootState.checkoutGatewayManager.loading = false;
                        }
                    }
                    else {
                        alert('Could not reach to server. Try Again');
                        context.rootState.checkoutGatewayManager.loading = false;
                    }

                })
                .catch(err => {
                    console.log(err);
                    alert('Could not connect to server. Try Again');
                    context.rootState.checkoutGatewayManager.loading = false;
                });
        }
    }
};

export default stripeGateway;

