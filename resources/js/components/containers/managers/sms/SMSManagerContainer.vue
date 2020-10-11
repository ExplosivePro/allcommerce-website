<template>
    <sms-manager-screen
        :view-mode="viewMode"
    ></sms-manager-screen>
</template>

<script>
import SmsManagerScreen from "../../../presenters/managers/sms/SMSManagerScreen";
export default {
    name: "SmsManagerContainer",
    components: {
        SmsManagerScreen
    },
    props: ['client', 'merchant'],
    data() {
        return {

        };
    },
    computed: {
        viewMode() {
            let mode = 'client';
            if(this.merchant !== undefined) {
                mode = 'merchant';
            }

            return mode;
        }
    },
    mounted() {
        console.log('SMSManagerContainer mounted!', [{client: this.client, merchant: this.merchant}]);
    }
}
</script>

<style scoped>
    @media screen {
        .sms-manager {
            height: 100%;
            width: 100%;
        }

        .inner-sms-manager {
            height: 100%;
            display: flex;
            flex-flow: column;
            justify-content: center;
            align-items: center;
        }
    }
</style>
