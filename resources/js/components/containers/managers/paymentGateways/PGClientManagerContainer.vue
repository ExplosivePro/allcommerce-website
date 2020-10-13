<template>
    <manager-screen
        :content-view="getView"
        :providers="availableProviders"
        :provider-info="activeProviderInfo"
        @load-provider="loadProvider"
    ></manager-screen>
</template>

<script>
import ManagerScreen from "../../../presenters/managers/paymentGateways/PGClientManagerScreen";

import { mapActions, mapMutations, mapGetters } from 'vuex';

export default {
    name: "PGClientManagerContainer",
    components: {
        ManagerScreen
    },
    watch: {
        clientContentView(flag) {
            let page = flag ? 'providers' : 'reports';
            console.log('Changing content page to '+ page);
        }
    },
    data() {
        return {
            availableProviders: [
                {
                    'title': 'Dry Run Gateway',
                    status: 'Enabled',
                    type: 'Credit Card',
                    disabled: false,
                },
                {
                    'title': 'Stripe',
                    status: 'Not Set Up',
                    type: 'Credit Card',
                    disabled: false,
                },
                {
                    'title': 'BrainTree (A PayPal Company)',
                    status: 'Coming Soon!',
                    type: 'Credit Card',
                    disabled: true,
                },
                {
                    'title': 'PayPal Express',
                    status: 'Coming Soon!',
                    type: 'Express Pay',
                    disabled: true,
                },
                {
                    'title': 'Amazon Pay',
                    status: 'Coming Soon!',
                    type: 'Express Pay',
                    disabled: true,
                },
                {
                    'title': 'Shopify Payments',
                    status: 'Coming Soon!',
                    type: 'Express Pay',
                    disabled: true,
                },
                {
                    'title': 'Sezzle',
                    status: 'Coming Soon!',
                    type: 'Installment Pay',
                    disabled: true,
                },
                {
                    'title': 'Affirm',
                    status: 'Coming Soon!',
                    type: 'Installment Pay',
                    disabled: true,
                },
                {
                    'title': 'AfterPay',
                    status: 'Coming Soon!',
                    type: 'Installment Pay',
                    disabled: true,
                },
            ],
            providerInfo: [
                {
                    desc: 'AllCommerce\'s Built-In Dry Run Gateway, needs no configuration and is always available. However, you cannot use it when you are ready to take payments. Assign it to your shop(s) to demo your new payment pages!',
                    fields: [],
                    type: 'Credit Card Gateway'
                },
                {
                    desc: 'Stripe Payment Gateway',
                    fields: [
                        {
                            name: 'Stripe API Key',
                            desc: 'Access Key for Accessing the Stripe API',
                            type: 'text',
                            value: ''
                        },
                        {
                            name: 'Live/Production Mode',
                            desc: 'Set Live or Test Mode Here',
                            type: 'select',
                            options: {live: 'Live Mode', test: 'Test Mode'},
                            value: ''
                        },
                    ],
                    type: 'Credit Card Gateway'
                }
            ],
            activeProviderInfo: ''
        };
    },
    computed: {
        ...mapGetters({
            getView: 'paymentGatewaysManager/getClientContentView'
        }),
    },
    methods: {
        ...mapActions({
            setAsideBar: 'asidebar/setContextTabActiveComponent'
        }),
        ...mapMutations({
            setAsideTitle: 'asidebar/contextTab/setTitle'
        }),
        loadProvider(idx) {
            console.log('Selected provider - ' + this.availableProviders[idx].title)
            this.activeProviderInfo = this.providerInfo[idx];
        }
    },
    mounted() {
        this.setAsideBar('aside-pg-manager-context-tab')
        this.setAsideTitle('Payment Gateways Manager - Account View')

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
