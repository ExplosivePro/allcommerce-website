import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const shopify = {
    namespaced: true,
    state() {
        return {
            installed: ''
        };
    },
    mutations: {
        setInstalled(state, flag) {
            state.installed = flag;
        }
    },
    getters: {

    },
    actions: {
        getInstallStatus(context, shopId) {
            console.log('pinging for shopify install status - ' + shopId);

            let url = '/access/shop/shopify/install-status';

            let payload = {
                shopId: shopId
            };

            axios.post(url, payload)
                .then(res => {
                    console.log('Install Status response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if ('success' in data) {
                            if (data['success']) {
                                console.log('Response Success!');
                                context.commit('setInstalled', data.installed);
                            }
                            else {
                                alert(data['reason']);
                                context.commit('setInstalled', false);
                            }
                        } else {
                            // unknown response
                            alert('Unknown Response from Anchor. Please Try Again.');
                            context.commit('setInstalled', false);
                        }
                    }
                })
                .catch(e => {
                    console.log(e);
                    alert('Could not communicate with Anchor. Please Try Again.');
                    context.commit('setInstalled', false);
                })
        }
    }
};

export default shopify;
