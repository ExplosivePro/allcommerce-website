<template>
    <pg-manager-screen
        :view-mode="viewMode"
        :height="contentHeight"
    ></pg-manager-screen>
</template>

<script>
import PgManagerScreen from "../../../presenters/managers/paymentGateways/PGManagerScreen";
import { mapActions, mapGetters, mapMutations } from "vuex";

export default {
    name: "PGManagerContainer",
    components: {
        PgManagerScreen
    },
    props: ['client', 'merchant', 'shops', 'gateways', 'clientEnabledGateways'],
    watch: {
        screenHeight(h) {
            console.log('Watching screenHeight update to ' +h);
            this.contentHeight['--sHeight']  = this.desktopScreenHeight+'px';
            this.contentHeight['--msHeight'] = this.mobileScreenHeight+'px';
        }
    },
    data() {
        return {
            contentHeight: {
                '--sHeight': this.desktopScreenHeight+'px',
                '--msHeight': this.mobileScreenHeight+'px',
            }
        };
    },
    computed: {
        ...mapGetters({
            screenWidth: 'screenWidth',
            screenHeight: 'screenHeight',
        }),
        mobileScreenHeight() {
            let h = (this.screenHeight * 0.65);

            if(h > 550) {
                h = (this.screenHeight * 0.725);
            }

            return h
        },
        desktopScreenHeight() {
            let h = (this.screenHeight * 0.675);

            return h
        },
        viewMode() {
            let mode = 'client';
            if(this.merchant !== undefined) {
                mode = 'merchant';
                this.setMerchantMode(true);
                this.setMerchantShops(this.shops)
            }

            return mode;
        }
    },
    methods: {
        ...mapMutations({
            setMerchantMode: 'paymentGatewaysManager/merchantMode',
            setMerchantShops: 'paymentGatewaysManager/merchantShops',
            setCreditProviders: 'paymentGatewaysManager/creditProviders',
            setExpressProviders: 'paymentGatewaysManager/expressProviders',
            setInstallmentProviders: 'paymentGatewaysManager/installmentProviders',
            setClientEnabledGateways: 'paymentGatewaysManager/clientEnabledGateways',
        })
    },
    mounted() {
        this.contentHeight = {
            '--sHeight': this.desktopScreenHeight+'px',
            '--msHeight': this.mobileScreenHeight+'px',
        }

        if(this.gateways !== undefined) {
            this.setCreditProviders(this.gateways.credit);
            this.setExpressProviders(this.gateways.express);
            this.setInstallmentProviders(this.gateways.install);
            this.setClientEnabledGateways(this.clientEnabledGateways)
        }

        console.log('Payment Gateways ManagerContainer mounted!', [{client: this.client, merchant: this.merchant}]);
    }
}
</script>

<style>
@media screen {

}

@media screen and (max-width: 999px) {
    .c-main .content {
        height: auto; /*var(--msHeight)*/;
    }
}

@media screen and (min-width: 1000px) {}
</style>

<style scoped>
@media screen {}
@media screen and (max-width: 999px) {}
@media screen and (min-width: 1000px) {}
</style>
