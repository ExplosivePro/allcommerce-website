<template>
    <div class="fb-roi-widget">
        <div class="inner-roi-widget">
            <div class="loading-section errrr" v-if="!loading && (errorMsg !== '')">
                <div class="inner-loading">
                    <div class="spinny-loader">
                        <div class="center-wrapper" @mouseover="sillyErrorIcon(true)" @mouseleave="sillyErrorIcon(false)">
                            <i :class="errorIcon" @click="sillyErrorIcon(errorIcon === 'fad fa-dizzy')"></i>
                            <p>{{ errorMsg }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loading-section" v-if="loading">
                <div class="inner-loading-section">
                    <sexy-hurricane :loading-msg="loadingMsg"></sexy-hurricane>
                </div>
            </div>
            <google-widget
                report="google"
                :data="dataFrame"
                :date="date"
            v-else></google-widget>
        </div>
    </div>
</template>

<script>
import SexyHurricane from "../../../presenters/widgets/loading/SexyHurricane";
import GoogleWidget from "../../../presenters/widgets/kpi-sales/ROIReport";
import {mapActions, mapState} from "vuex";

export default {
    name: "GoogleROIWidgetContainer",
    components: {
        GoogleWidget,
        SexyHurricane
    },
    watch: {
        loading(flag) {
            if((!flag) && (this.report !== '')) {
                if('sales-by-market-cnb' in this.report) {
                    this.date = this.report['sales-by-market-cnb'].date
                    this.curateReport(this.report['sales-by-market-cnb']);
                }
                else {
                    this.dataFrame = '';
                }
            }
        }
    },
    props: ['clientId'],
    data() {
        return {
            loadingMsg: 'Loading Google ROI Report...',
            errorIcon: 'fad fa-angry',
            dataFrame: '',
            date: ''
        }
    },
    computed: {
        ...mapState('kpi', ['loading', 'errorMsg', 'report']),
    },
    methods: {
        ...mapActions({
            getKPIReport: 'kpi/getKPIReport'
        }),
        sillyErrorIcon(flag) {
            if(flag) {
                this.errorIcon = 'fad fa-sad-cry';
            }
            else {
                this.errorIcon = 'fad fa-dizzy';
            }
        },
        curateReport(report) {
            if('roi' in report) {
                console.log('Passing report frame to view - ',report)
                this.dataFrame = report;
            }
            else {
                this.dataFrame = '';
            }
        }
    },
    mounted() {
        if(this.report === '') {
            console.log('Empty report');
            if(this.loading) {
                console.log('Loading tho, so gonna chill')
            }
            else {
                console.log('Props to the mounted GoogleROIWidgetContainer - ', this.report);
                this.getKPIReport(this.clientId);
            }
        }
        else if((!this.loading)) {
            if('sales-by-market-cnb' in this.report) {
                this.date = this.report['sales-by-market-cnb'].date
                this.curateReport(this.report['sales-by-market-cnb']);
            }
            else {
                this.dataFrame = '';
            }
        }
    }
}
</script>

<style scoped>
@media screen {
    .google-roi-widget {
        width: 100%;
        height: 100%;
    }

    .inner-roi-widget {
        margin: 5%;
    }

    .loading-section {

    }

    .errrr .spinny-loader {
        text-align: center;
    }

    .errrr .spinny-loader i {
        font-size: 3em;
    }

    .spinny-loader p {
        margin-top: 0.5em;
        font-weight: thin;
        font-size: 1em;
        text-transform: uppercase;
        letter-spacing: 0.1em
    }
}
</style>
