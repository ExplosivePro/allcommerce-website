<template>
    <div class="kpi-report-container">
        <div class="inner-kpi-report">
            <kpi-sales v-if="!loading && (errorMsg === '')"
                       :report="report"
                       :report-date="reportDate"
                       :roi-mode="roiMode"
                       :roi-options="roiOptions"
            ></kpi-sales>
            <div class="loading errrr" v-if="!loading && (errorMsg !== '')">
                <div class="inner-loading">
                    <div class="spinny-loader">
                        <div class="center-wrapper">
                            <i class="fad fa-bug faa-ring animated faa-slow"></i>
                            <p>{{ errorMsg }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loading" v-if="loading">
                <div class="inner-loading">
                    <sexy-hurricane
                        :loading-msg="loadingMsg"
                    ></sexy-hurricane>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SexyHurricane from "../../../presenters/widgets/loading/SexyHurricane";
import KpiSales from "../../../presenters/reports/kpi-sales/KPISalesFullReportScreenComponent";
import { mapMutations, mapState, mapActions } from 'vuex';

export default {
    name: "KPIReportContainer",
    components: {
        KpiSales,
        SexyHurricane
    },
    props: ['clientId', 'startingRoiMode'],
    watch: {
        reportDate(date) {
            if(!this.ready) {
                console.log(`First report of the load received! It belongs to date ${date}...ready to go!`);
                this.ready = true;
                this.initRoiMode(this.startingRoiMode);
            }
            else {
                console.log(`report date changed to ${date}...reaching out to server for update...`);
                this.getKPIReport(this.clientId);
            }
        },
        loading(flag) {

        }
    },
    data() {
        return {
            loadingMsg: 'Getting Latest Performance Reports...',
            ready: false
        };
    },
    computed: {
        ...mapState('kpi', ['loading', 'errorMsg', 'report', 'reportDate', 'roiMode', 'roiOptions']),
    },
    methods: {
        initAsideBar() {
            this.setContextTabActiveComponent('kpi-aside-context')
        },
        ...mapActions({
            setContextTabActiveComponent: 'asidebar/setContextTabActiveComponent',
            getKPIReport: 'kpi/getKPIReport',
            roiModeTriggered: 'kpi/roiModeTriggered',
        }),
        ...mapMutations({
            setRoiMode: 'kpi/roiMode',
            setRoiOptions: 'kpi/roiOptions'
        }),
        initRoiMode(mode) {
            console.log('initRoiMode - starting mode to '+mode);
            switch(mode) {
                case 'all':
                    this.roiModeTriggered({
                        'none': false,
                        'all': true,
                        'fb': true,
                        'google': true,
                    });
                    break;

                case 'fb':
                case 'facebook':
                    this.roiModeTriggered({
                        'none': false,
                        'all': false,
                        'fb': true,
                        'google': false,
                    });
                    break;

                case 'google':
                    this.roiModeTriggered({
                        'none': false,
                        'all': false,
                        'fb': false,
                        'google': true,
                    });
                    break;

                default:
                    this.setRoiMode(false);
                    this.setRoiOptions({
                        'none': true,
                        'all': false,
                        'fb': false,
                        'google': false,
                    });
            }
        }
    },
    mounted() {
        this.initAsideBar();
        this.getKPIReport(this.clientId);
        console.log('KPIReportContainer mounted! '+ this.clientId)
    }
}
</script>

<style scoped>
@media screen {
    .kpi-report-container {
        width: 100%;
        height: 100%;
    }

    .inner-kpi-report {
        margin: 1%;
        display: flex;
        flex-flow: column;
        justify-content: center;
        align-items: center;
    }

    .errrr .spinny-loader {
        text-align: center;
    }

    .errrr .spinny-loader i {
        font-size: 3em;
    }

    .errrr .spinny-loader p {
        margin-top: 0.5em;
        font-weight: thin;
        font-size: 1.25em;
        text-transform: uppercase;
        letter-spacing: 0.1em
    }
}

@media screen and (max-width: 999px) {
    @media screen and (min-width: 500px) {
        .loading {
            margin-top: 25%;
        }
    }

    @media screen and (max-width: 499px) {
        .loading {
            margin-top: 35%;
        }
    }

}

@media screen and (min-width: 1000px) {
    .loading {
        margin-top: 20%;
    }
}
</style>
