import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const checkoutFunnelsManager = {
    namespaced: true,
    state() {
        return {
            activeShop: '',
            shopProductOptions: ''
        };
    },
    mutations: {
        activeShop(state, uuid) {
            console.log('Setting activeShop to '+ uuid);
            state.activeShop = uuid;
        },
        shopProductOptions(state, options) {
            console.log('Setting shopProductOptions to ', options);
            state.shopProductOptions = options;
        }
    },
    getters: {
        activeShop(state) {
            return state.activeShop;
        },
        shopProductOptions(state) {
            return state.shopProductOptions;
        }
    },
    actions: {
        fetchProductsForActiveShop(context) {
            let url = `/access/shop/products?shop_uuid=${context.state.activeShop}`;
            console.log(`Pinging ${url}`);

            axios.get(url)
                .then(res => {
                    console.log('fetchProductsForActiveShop response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if(data.length > 0) {
                            context.commit('shopProductOptions', data);
                        }
                        else {
                            if(context.state.shopProductOptions === '') {
                                context.commit('shopProductOptions', []);
                            }
                            else {
                                context.commit('shopProductOptions', '');
                            }
                        }
                    }
                })
                .catch(e => {
                    console.log(e);
                    alert('Could not communicate with AllCommerce. No Products Available. Please Try Again.');
                    if(context.state.shopProductOptions === '') {
                        context.commit('shopProductOptions', []);
                    }
                    else {
                        context.commit('shopProductOptions', '');
                    }
                });
        }
    }
};

export default checkoutFunnelsManager;
