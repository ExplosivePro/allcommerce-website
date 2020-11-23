import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const checkoutFunnels = {
    namespaced: true,
    modules: {},
    state() {
        return {
            activeShop: '',
            productOptions: '',
            loading: false,
        }
    },
    mutations: {
        activeShop(state, uuid) {
            state.activeShop = uuid;
        },
        loading(state, flag) {
            state.loading = flag;
        },
        productOptions(state, options) {
            state.productOptions = options;
        }
    },
    getters: {
        activeShop(state) {
            return state.activeShop;
        },
        loading(state) {
            return state.loading
        },
        productOptions(state) {
            return state.productOptions;
        }
    },
    actions: {
        fetchShopProducts(context, shopId) {
            context.commit('activeShop', shopId);
            context.commit('productOptions',{
                '': 'Loading Shop Products. Hang on a Sec.'
            });
            context.commit('loading', true);
            let payload = {'shop_id': shopId}
            let url = '/access/checkout-funnels/products'
            axios.post(url, payload)
                .then(res => {
                    console.log('checkoutFunnels fetchShopProducts call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                context.commit('productOptions',data['options']);
                                context.commit('loading', false);
                            }
                            else {
                                context.commit('productOptions',data['options']);
                            }
                        }
                        else {
                            context.commit('productOptions',{
                                '': 'An error occurred. Please try again'
                            });
                        }
                    }
                    else {
                        context.commit('productOptions',{
                            '': 'Unknown Response. Please try again'
                        });
                    }
                })
                .catch(err => {
                    console.log('checkoutFunnels fetchShopProducts failed response - ', err);

                    context.commit('productOptions',{
                        '': 'There was an error. Please try again'
                    });
                })
        }
    }
};

export default checkoutFunnels;
