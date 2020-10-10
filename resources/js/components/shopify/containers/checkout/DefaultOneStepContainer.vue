<template>
    <one-step
        :last4="getClickDataPhone"
        :show-form="showForm"
        :loading="loading"
        :error="errorMsg"
        :failed="failed"
        :expired="expired"
        :time-to-expire="countdown"
        :success="success"
        :allow-resets="allowResets"
        :customer="customerName"
        @unshow-form="unshowForm"
        @reset-form="resetForm"
        @submit-form="submit"
    ></one-step>
</template>

<script>
    import OneStep from "../../screens/checkout/DefaultExperienceOneStep";
    import { mapActions, mapGetters, mapMutations } from 'vuex';

    export default {
        name: "DefaultOneStepContainer",
        components: {
            OneStep
        },
        props:['showForm'],
        watch: {
            oneClickData(data) {
                console.log('DefaultOneStepContainer getting new oneclick data!', data);
                if(data !== '') {
                    this.last4 = data.phone;
                    this.setClickData(data);
                }
            },
            oneClickResults(data) {
                console.log('Receiving new one-click results..', [data]);
                if(data !== '') {
                    this.success = true;
                    this.customerName = `${data.shipping['first_name']} ${data.shipping['last_name']}`;
                }
            },
            countdown(count) {
                if((count > 0) && (!this.failed)) {
                    let _this = this;
                    setTimeout(function() {
                        // console.log('sec left- '+ count);
                        _this.reduceCountdown();
                    } , 1000);
                }
                else {
                    this.expired = true;
                    this.setFailed(true);
                }
            },
            showForm(flag) {
                if(flag) {
                    console.log('Starting countdown');
                    this.reduceCountdown();
                }
            }
        },
        data() {
            return {
                last4: '',
                success: false,
                customerName: '',
                expired: false
            };
        },
        computed: {
            ...mapGetters({
                oneClickData: 'leadManager/oneClickData',
                loading: 'oneClickManager/loading',
                errorMsg: 'oneClickManager/errorMsg',
                failed: 'oneClickManager/failed',
                allowResets: 'oneClickManager/allowResets',
                oneClickResults: 'oneClickManager/oneClickResults',
                countdown: 'oneClickManager/countdown'
            }),
            getClickDataPhone() {
                if((this.oneClickData !== undefined) && ('phone' in this.oneClickData)) {
                    return this.oneClickData.phone
                }
                else {
                    return 'xxxx';
                }
            }
        },
        methods: {
            unshowForm() {
                this.toggle(false);
            },
            ...mapActions({
                toggle: 'oneClickManager/toggleOneClickMode',
                submit: 'oneClickManager/submitCheckoutCode',
                resetForm: 'oneClickManager/resendCheckoutCode',
            }),
            ...mapMutations({
                setClickData: 'oneClickManager/clickData',
                reduceCountdown: 'oneClickManager/reduceCountdown',
                setFailed: 'oneClickManager/failed'
            })
        }
    }
</script>

<style scoped>

</style>
