import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const membershipPurchase = {
    namespaced: true,
    modules: {},
    state() {
        return {
            loading: false,
            membershipLoading: false,
            creditsResult: '',
            membershipResult: '',
            statusCode: 200,
            activeScreen: 'members'
        };
    },
    mutations: {
        loading(state, flag) {
            state.loading = flag;
        },
        membershipLoading(state, flag) {
            state.membershipLoading = flag;
        },
        membershipResult(state, resp) {
            state.membershipResult = resp;
        },
        creditsResult(state, resp) {
            state.creditsResult = resp;
        },
        statusCode(state, resp) {
            state.statusCode = resp;
        },
        activeScreen(state, screen) {
            state.activeScreen = screen;
        }
    },
    getters: {
        getLoading(state) {
            return state.loading;
        },
        getMembershipLoading(state) {
            return state.membershipLoading;
        },
        getCreditsResult(state) {
            return state.creditsResult;
        },
        getMembershipResult(state) {
            return state.membershipResult;
        },
        getStatusCode(state) {
            return state.statusCode;
        },
        getActiveScreen(state) {
            return state.activeScreen;
        },
    },
    actions: {
        purchaseSuccess(context, data) {
            context.commit('creditsResult', true);
            context.commit('loading', false);
        },
        membershipPurchaseSuccess(context, data) {
            context.commit('membershipResult', true);
            context.commit('membershipLoading', false);
        },
        purchaseFail(context, err) {
            context.commit('statusCode', err.response.status);
            switch(err.response.status)
            {
                case 418:
                case 503:
                    context.commit('creditsResult', 'Failed. '+err.response.data.reason)
                    break;

                case 422:
                    let reason = '';
                    console.log('Errors -', err.response.data.errors)
                    for(let x in err.response.data.errors) {
                        reason = err.response.data.errors[x][0];
                        break;
                    }
                    context.commit('creditsResult', 'Failed. '+reason);
                    break;

                case 500:
                    context.commit('creditsResult', 'Failed. Server responded with a 500.')

            }
            console.log('Credit Purchase fail - ',err);
            context.commit('loading', false);
        },
        membershipPurchaseFail(context, err) {
            context.commit('statusCode', err.response.status);
            switch(err.response.status)
            {
                case 418:
                case 503:
                    context.commit('membershipResult', 'Failed. '+err.response.data.reason)
                    break;

                case 422:
                    let reason = '';
                    console.log('Errors -', err.response.data.errors)
                    for(let x in err.response.data.errors) {
                        reason = err.response.data.errors[x][0];
                        break;
                    }
                    context.commit('membershipResult', 'Failed. '+reason);
                    break;

                case 500:
                    context.commit('membershipResult', 'Failed. Server responded with a 500.')

            }
            console.log('Credit Purchase fail - ',err);
            context.commit('membershipLoading', false);
        },
        purchaseCredits(context, payload) {
            let _this = this;


            let url = '/access/memberships/credits/purchase';
            context.commit('creditsResult', '')

            axios.post(url, payload['attributes'])
                .then(function (response) {
                    console.log('Credit Purchase response -', response);
                    let data = response.data;
                    context.dispatch('purchaseSuccess', data);
                })
                .catch(err => {
                    context.dispatch('purchaseFail', err);
                });

        },
        purchaseMembership(context, payload) {
            let _this = this;

            let url = '/access/memberships/subscription/purchase';
            context.commit('membershipResult', '')

            axios.post(url, payload)
                .then(function (response) {
                    console.log('Membership Purchase response -', response);
                    let data = response.data;
                    context.dispatch('membershipPurchaseSuccess', data);
                })
                .catch(err => {
                    context.dispatch('membershipPurchaseFail', err);
                });
        }
    }
};

export default membershipPurchase;
