import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const kpi = {
    namespaced: true,
    state() {
        return {
            loading: false,
            errorMsg: '',
            report: '',
            reportDate: '',
            roiMode: false,
            roiOptions: {}
        };
    },
    mutations: {
        loading(state, flag) {
            state.loading = flag;
        },
        errorMsg(state, msg) {
            state.errorMsg = msg;
        },
        report(state, report) {
            state.report = report;
        },
        reportDate(state, date) {
            state.reportDate = date;
        },
        switchOutReport(state, reportSlug) {
            let answer = !state.report[reportSlug].show;
            console.log(`switching out report! ${reportSlug} from ${state.report[reportSlug].show} to ${answer}`);

            state.report[reportSlug].show = !state.report[reportSlug].show;

            if(state.roiMode) {
                if(reportSlug !== 'sales-by-market-cnb') {
                    setTimeout(function() { state.report[reportSlug].show = false; }, 100);
                }
                else {
                    setTimeout(function() { state.report['sales-by-market-cnb'].show = true; }, 100);
                }
            }
            /*
            if((state.roiMode === true) && (reportSlug === 'sales-by-market-cnb')) {
                setTimeout(function() { state.report[reportSlug].show = true; }, 100);
            }
            else if((state.roiMode === true) && (reportSlug !== 'sales-by-market-cnb')) {
                setTimeout(function() { state.report[reportSlug].show = false; }, 100);
            }

             */
        },
        roiMode(state, flag) {
            state.roiMode = flag;
        },
        roiOptions(state, options) {
            state.roiOptions = options;
        }
    },
    getters: {
        getLoading(state, getters) {
            return state.loading;
        }
    },
    actions: {
        processKPIData(context, report) {

            context.commit('errorMsg', '');
            context.commit('loading',false);

            for(let elem in report) {
                if('date' in report[elem]) {
                    context.commit('reportDate', report[elem].date);
                    break;
                }
            }

            for(let elem in report) {
                report[elem]['show'] = true;
            }

            context.commit('report', report);

            if(context.state.roiMode) {
                if(!context.state.roiOptions.none) {
                    console.log('Restoring ROI Mode', context.state.roiOptions);
                    setTimeout(function () { context.dispatch('roiModeTriggered', context.state.roiOptions); }, 100)
                }
            }
        },
        getKPIReport(context, clientId) {
            context.commit('loading',true);
            //context.commit('roiMode',false);

            let url = `/reports/${clientId}/kpi`;

            if(context.state.reportDate !== '') {
                url = url + `?date=${context.state.reportDate}`;
            }

            axios.get(url)
                .then(res => {
                    console.log('KPI report response - ', res);

                    if('data' in res) {
                        let data = res.data;

                        if ('success' in data) {
                            if (data['success']) {
                                context.commit('errorMsg', '');
                                context.dispatch('processKPIData', data['report']);
                                context.commit('loading',false);
                            } else {
                                context.commit('errorMsg', data['reason']);
                                context.commit('loading',false);
                            }
                        } else {
                            // unknown response
                            context.commit('errorMsg','Unknown Response from Anchor. Please Try Again.');
                            context.commit('loading',false);
                        }
                    }
                })
                .catch(e => {
                    console.log(e);
                    context.commit('errorMsg','Error - Unknown Response from Anchor. Please Try Again.');
                    context.commit('loading',false);
                });
        },
        roiModeTriggered(context, options) {
            for(let reportName in context.state.report) {
                if(reportName !== 'sales-by-market-cnb') {
                    if(context.state.report[reportName].show) {
                        context.commit('switchOutReport', reportName);
                    }
                }
            }

            context.commit('roiMode', true);
            context.commit('switchOutReport', 'sales-by-market-cnb');
            context.commit('roiOptions', options);
        },
        roiModeDisabled(context) {
            context.commit('roiMode', false);
            //context.commit('roiOptions', {});

            for(let reportName in context.state.report) {
                if(reportName !== 'sales-by-market-cnb') {
                    if(!context.state.report[reportName].show) {
                        context.commit('switchOutReport', reportName);
                    }
                }
            }
        }
    }
};

export default kpi;
