<template>
    <pg-manager-screen
        :view-mode="viewMode"
    ></pg-manager-screen>
</template>

<script>
import PgManagerScreen from "../../../presenters/managers/paymentGateways/PGManagerScreen";

export default {
    name: "PGManagerContainer",
    components: {
        PgManagerScreen
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
        console.log('Payment Gateways ManagerContainer mounted!', [{client: this.client, merchant: this.merchant}]);
    }
}
</script>

<style scoped>

</style>
