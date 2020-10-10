import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const oneClickManager = {
    namespaced: true,
    state() {
        return {
            loading: false,
            failed: false,
            active: false,
            allowResets: true,
            clickData: '',
            apiUrl: '',
            errorMsg: '',
            oneClickResults: '',
            attempts: 0,
            resets: 0,
            countdown: 120 //denoted in seconds
        };
    },
    mutations: {
        incrementReset(state) {
            state.resets++;

            if(state.resets === 1) {
                console.log('We won\'t resend any more codes :(');
                state.allowResets = false;
            }
        },
        incrementAttempts(state) {
            state.attempts++;

            if(state.attempts === 3) {
                console.log('This session has failed :(');
                state.failed = true;
            }
        },
        resetAttempts(state) {
            state.attempts = 0;
        },
        active(state, flag) {
            state.active = flag;
        },
        failed(state, flag) {
            state.failed = flag;

            if(flag) {
                state.loading = false;
            }
        },
        allowResets(state, flag) {
            state.allowResets = flag;
        },
        loading(state, flag) {
            state.loading = flag;
        },
        clickData(state, data) {
            state.clickData = data;
        },
        errorMsg(state, msg) {
            state.errorMsg = msg;
        },
        oneClickResults(state, res) {
            state.oneClickResults = res;
        },
        reduceCountdown(state) {
            state.countdown--;
        }
    },
    getters: {
        active(state) {
            return state.active;
        },
        loading(state) {
            return state.loading;
        },
        failed(state) {
            return state.failed;
        },
        errorMsg(state) {
            return state.errorMsg;
        },
        allowResets(state) {
            return state.allowResets;
        },
        oneClickResults(state) {
            return state.oneClickResults;
        },
        countdown(state) {
            return state.countdown;
        }
    },
    actions: {
        toggleOneClickMode(context, flag) {
            context.commit('active', flag);
        },
        resendCheckoutCode(context) {
            context.commit('loading', true);
            context.commit('errorMsg', '');

            let url = `${context.state.apiUrl}/api/checkout/shopify/one-click/resend`;

            let payload = {
                data: context.state.clickData
            };

            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('OneClick resendCheckoutCode call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                context.commit('errorMsg', '');
                                context.commit('resetAttempts');
                                context.commit('incrementReset');
                            }
                            else {
                                context.commit('errorMsg', data['reason']);
                                context.commit('oneClickResults', '');
                            }
                        }
                        else {
                            context.commit('errorMsg', 'An Error Occurred. Please try again');
                            context.commit('oneClickResults', '');
                        }
                    }
                    else {
                        context.commit('errorMsg', 'Unknown Response From Server');
                        context.commit('oneClickResults', '');
                    }

                    context.commit('incrementAttempts');
                    context.commit('loading', false);
                })
                .catch(err => {
                    console.log(err);

                    switch(err.response.status) {
                        case 429:
                            context.commit('errorMsg', err.response.data.reason);
                            context.commit('failed', true);
                            break;

                        default:
                            context.commit('errorMsg', err);
                            context.commit('loading', false);
                    }

                    context.commit('incrementAttempts');
                    context.commit('oneClickResults', '');
                });
        },
        submitCheckoutCode(context, code) {
            context.commit('loading', true);
            context.commit('errorMsg', '');
            console.log('Submitting check for code '+ code, context.state.clickData);

            let url = `${context.state.apiUrl}/api/checkout/shopify/one-click/validate`;

            let payload = {
                code: code,
                data: context.state.clickData
            };

            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('OneClick submitCheckoutCode call response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if('success' in data) {
                            if(data['success']) {
                                context.commit('errorMsg', '');
                                context.commit('oneClickResults', data['results']);
                            }
                            else {
                                context.commit('errorMsg', data['reason']);
                                context.commit('oneClickResults', '');
                            }
                        }
                        else {
                            context.commit('errorMsg', 'An Error Occurred. Please try again');
                            context.commit('oneClickResults', '');
                        }
                    }
                    else {
                        context.commit('errorMsg', 'Unknown Response From Server');
                        context.commit('oneClickResults', '');
                    }

                    context.commit('incrementAttempts');
                    context.commit('loading', false);
                })
                .catch(err => {
                    console.log(err);
                    context.commit('errorMsg', err);
                    context.commit('incrementAttempts');
                    context.commit('loading', false);
                    context.commit('oneClickResults', '');
                });
        }
    }
};

export default oneClickManager;
