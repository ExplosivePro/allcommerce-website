<template>
    <kpi-report
        :loading="loading"
        :error="errorMsg"
        :report="report"
    ></kpi-report>
</template>

<script>
    import KpiReport from '../../../presenters/widgets/kpi-sales/KPIReport';
    import { mapState, mapActions } from 'vuex';
    export default {
        name: "KPISalesContainer",
        components: {
            KpiReport
        },
        props: ['clientId'],
        data() {
            return {
            };
        },
        computed: {
            ...mapState('kpi', ['loading', 'errorMsg', 'report']),
        },
        methods: {
            ...mapActions({
                getKPIReport: 'kpi/getKPIReport'
            })
        },
        mounted() {
            if(this.report === '') {
                console.log('Empty report');
                if(this.loading) {
                    console.log('Loading tho, so gonna chill')
                }
                else {
                    console.log('KPISalesContainer mounted!');
                    this.getKPIReport(this.clientId);
                }
            }
            else
            {
                console.log('KPISalesContainer mounted!', this.report);
            }


        }
    }
</script>

<style scoped>
    h1 {
        text-align: center;
    }
</style>
