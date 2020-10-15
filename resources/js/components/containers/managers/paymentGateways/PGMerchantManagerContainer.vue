<template>
    <manager-screen
        :shops="getMerchantShops"
        :gateways="availableProviders"
        @shop-triggered=""
    ></manager-screen>
</template>

<script>
import ManagerScreen from "../../../presenters/managers/paymentGateways/PGMerchantManagerScreen";

import { mapActions, mapMutations, mapGetters } from 'vuex';

export default {
    name: "PGMerchantManagerContainer",
    components: {
        ManagerScreen
    },
    watch: {

    },
    data() {
        return {}
    },
    computed: {
        ...mapGetters({
            getMerchantShops: 'paymentGatewaysManager/merchantShops',
            creditProviders: 'paymentGatewaysManager/creditProviders',
            expressProviders: 'paymentGatewaysManager/expressProviders',
            installmentProviders: 'paymentGatewaysManager/installmentProviders'
        }),
        availableProviders() {
            return {
                credit: this.creditProviders,
                express: this.expressProviders,
                install: this.installmentProviders,
            }
        }
    },
    methods: {
        ...mapActions({
            setAsideBar: 'asidebar/setContextTabActiveComponent'
        }),
        ...mapMutations({
            setAsideTitle: 'asidebar/contextTab/setTitle'
        })
    },
    mounted() {
        //this.setAsideBar('aside-pg-manager-context-tab')
        this.setAsideTitle('Payment Gateways Manager - Merchant View')

        /*
        setTimeout(function() {
            $('.c-header-toggler').click();
        }, 1500);

         */
    }
}
</script>

<style scoped>

</style>
