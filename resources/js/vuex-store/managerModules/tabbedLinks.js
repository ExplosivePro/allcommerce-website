import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);


const tabbedLinks = {
    namespaced: true,
    state() {
        return {
            loading: false,
            feature: '',
            links: []
        };
    },
    mutations: {
        loading(state, flag) {
            console.log('Mutating loading to '+ flag);
            state.loading = flag;
        },
        feature(state, name) {
            console.log('Mutating tabbedLinks feature to '+ name);
            state.feature = name;
        },
        links(state, links) {
            console.log('Mutating tabbedLinks  to ', links);
            state.links = links;
        }
    },
    getters: {
        loading(state) {
            return state.loading;
        },
        feature(state) {
            return state.feature;
        },
        accessUri(state) {
            let results = '';

            switch(state.feature) {
                case 'sms':
                    results = 'sms-manager';
                    break;
            }

            return results;
        }
    },
    actions: {
        fetchLinks({commit, getters, dispatch}) {
            commit('loading', true);

            let payload = {
                feature: getters.feature
            };

            let url = `/access/${getters.accessUri}/tabbed-links`;
            console.log(`Pinging ${url}`, payload);

            axios.post(url, payload)
                .then(res => {
                    console.log('Lead fetchLinks call response - ', res);

                    if('data' in res) {
                        let data = res['data'];
                        if(data.links.length > 0)
                        {
                            dispatch('curateLinks', data.links);
                        }
                        else {
                            console.log('No Links. settingDefaultLinks');
                            dispatch('setDefaultLinks');
                        }
                    }
                    else {
                        console.log('Bad Response. settingDefaultLinks');
                        dispatch('setDefaultLinks');
                    }
                })
                .catch(err => {
                    console.log('Server Error. settingDefaultLinks', err);
                    dispatch('setDefaultLinks');
                });
        },
        curateLinks({commit}, links) {
            commit('links', links);
            commit('loading', false);
        },
        setDefaultLinks({getters, commit}) {
            let links = [];
            switch(getters.feature) {
                case 'sms' :
                    console.log('settingDefaultLinks for sms-manager');
                    links.push({
                        title: 'SMS Manager',
                        url: '',
                        active: true
                    });
                break;

                case 'payment-gateways' :
                    console.log('settingDefaultLinks for payment-gateways');
                    links.push({
                        title: 'Payment Gateways',
                        url: '',
                        active: true
                    });
                break;

                case '1-click' :
                    console.log('settingDefaultLinks for 1-click');
                    links.push({
                        title: '1-Click Manager',
                        url: '',
                        active: true
                    });
                break;
            }

            commit('links', links);
            commit('loading', false);
        }
    }
};

export default tabbedLinks;
