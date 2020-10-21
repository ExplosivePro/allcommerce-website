import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const dryRunGateway = {
    namespaced: true,
    state() {
        return {
            name: 'Dry Run Test Gateway',
            apiUrl: ''
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
        }
    },
    actions: {
        auth(context, payload) {
            console.log('delete this ', payload);

            let url = `${context.state.apiUrl}/api/checkout/payment/credit/auth`;
            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('dryRun Gateway auth call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                alert('succes or something.');
                                context.rootState.checkoutGatewayManager.loading = false;

                                // @todo - redirect to redirect key
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
                        context.rootState.instance.loading = false;
                    }

                })
                .catch(err => {
                    console.log(err);
                    alert('Could not connect to server. Try Again');
                    context.rootState.instance.loading = false;
                });
        }
    }
};

export default dryRunGateway;

