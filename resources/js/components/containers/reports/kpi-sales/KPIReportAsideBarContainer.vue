<template>
    <div class="kpi-aside-bar-tab">
        <div class="inner-aside-bar">
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
            <kpi-aside
                :report-date="reportDate"
                @date-changed="getReportForDate"
                :reports="report"
                @toggle-report="toggleReport"
                @toggle-roi="toggleROI"
                :new-roi-options="newRoiOptions"
            v-else></kpi-aside>
        </div>
    </div>
</template>

<script>

import SexyHurricane from "../../../presenters/widgets/loading/SexyHurricane";
import KpiAside from "../../../presenters/reports/kpi-sales/KPISalesFullReportOptionsAsideComponent";
import {mapMutations, mapState, mapActions} from 'vuex';

export default {
    name: "KPIReportAsideBarContainer",
    components: {
        KpiAside,
        SexyHurricane
    },
    watch: {
        roiOptions(options) {
            console.log('aside bar detecting new options - ', options);
            this.newRoiOptions = options;
        }
    },
    data() {
        return {
            loadingMsg: 'Loading Performance Options...',
            errorIcon: 'fad fa-angry',
            newRoiOptions: ''
        }
    },
    computed: {
        ...mapState('kpi', ['loading', 'errorMsg', 'report','reportDate', 'roiOptions', 'roiMode']),
    },
    methods: {
        ...mapMutations({
            setTitle: 'asidebar/contextTab/setTitle',
            setReportDate: 'kpi/reportDate',
            switchOutReport: 'kpi/switchOutReport'
        }),
        ...mapActions({
            roiModeTriggered: 'kpi/roiModeTriggered',
            roiModeDisabled: 'kpi/roiModeDisabled'
        }),
        sillyErrorIcon(flag) {
            if(flag) {
                this.errorIcon = 'fad fa-sad-cry';
            }
            else {
                this.errorIcon = 'fad fa-dizzy';
            }
        },
        getReportForDate(date) {
            this.setReportDate(date);
        },
        toggleReport(slug) {
            if(slug in this.report)
            {
                this.switchOutReport(slug);
            }
            else {
                console.log('Cannot locate '+slug+' in the report obj.')
            }
        },
        toggleROI(options) {
            console.log('ROI options - ', options);
            if(options.none) {
                this.roiModeDisabled();
            }
            else {
                if((options.all) || (options.fb) || (options.google)) {
                    this.roiModeTriggered(options);
                }
            }
        }
    },
    mounted() {
        this.setTitle('Performance Report Options');
        console.log('KPIReportAsideBarContainer mounted!')
    }

}
</script>

<style scoped>
    @media screen {
        .kpi-aside-bar-tab {
            width: 100%;
            height: 100%;
        }

        .inner-aside-bar {
            margin: 5%;
        }

        .loading-section {
            margin-top: 100%;
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
