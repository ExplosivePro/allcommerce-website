<template>
    <engagement-chart
        :loading="loading"
        :error="errorMsg"
        :reports="reports"
    ></engagement-chart>
</template>

<script>
import EngagementChart from "../../../presenters/widgets/mailchimp/MailchimpEngagementChart";

 export default {
     name: "MailchimpWidgetContainer",
     components: {
        EngagementChart
     },
     props: ['clientId'],
     watch: {},
     data() {
         return {
             loading: false,
             errorMsg: '',
             reports: '',
         };
     },
     computed: {},
     methods: {
         processEngagementData(report) {
             this.reports = report;
         },
         ajaxGetEngagementReport() {
             let _this = this;
             this.loading = true;

             axios.get(`reports/${this.clientId}/mailchimp/engage`)
                 .then(res => {
                     console.log('Mailchimp Engagement report response - ', res);

                     if('data' in res) {
                         let data = res.data;

                         if ('success' in data) {
                             if (data['success']) {
                                 //_this.errorMsg = 'Processing Data';
                                 _this.errorMsg = '';
                                 _this.processEngagementData(data['reports']);
                                 _this.loading = false;
                             }
                             else {
                                 _this.errorMsg = data['reason'];
                                 _this.loading = false;
                             }
                         } else {
                             // unknown response
                             _this.errorMsg = 'Unknown Response from Anchor. Please Try Again.';
                             _this.loading = false;
                         }
                     }
                 })
                 .catch(e => {
                     console.log(e);
                     _this.errorMsg = 'Could not communicate with Anchor. Please Try Again.'
                     _this.loading = false;
                 })
         }
     },
     mounted() {
         this.ajaxGetEngagementReport();
        console.log('Mailchimp Widget Mounted!');
    }
}
</script>

<style scoped>

</style>
