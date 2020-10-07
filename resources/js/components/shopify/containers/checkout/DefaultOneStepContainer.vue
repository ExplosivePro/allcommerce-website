<template>
    <one-step
        :last4="getClickDataPhone"
        :show-form="showForm"
        :loading="loading"
        :error="errorMsg"
        :failed="failed"
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
            }
        },
        data() {
            return {
                last4: '',
                success: false,
                customerName: ''
            };
        },
        computed: {
            ...mapGetters({
                oneClickData: 'leadManager/oneClickData',
                loading: 'oneClickManager/loading',
                errorMsg: 'oneClickManager/errorMsg',
                failed: 'oneClickManager/failed',
                allowResets: 'oneClickManager/allowResets',
                oneClickResults: 'oneClickManager/oneClickResults'
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
                resetForm: 'oneClickManager/resendCheckoutCode'
            }),
            ...mapMutations({
                setClickData: 'oneClickManager/clickData'
            })
        }
    }
</script>

<style scoped>

</style>
