<template>
    <manager-screen
        :content-view="getView"
        :providers="availableProviders"
        :provider-info="activeProviderInfo"
        @load-provider="loadProvider"
    ></manager-screen>
</template>

<script>
import ManagerScreen from "../../../presenters/managers/sms/SMSClientManagerScreen";

import { mapActions, mapMutations, mapGetters } from 'vuex';

export default {
    name: "SMSClientManagerContainer",
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
                    'title': 'AllCommerce/Twilio SMS',
                    cost: '$0.01/per msg',
                    status: 'Enabled',
                    disabled: false,
                },
                {
                    'title': 'Twilio SMS',
                    'cost': 'Free!',
                    status: 'Not Set Up',
                    disabled: false,
                },
                {
                    'title': 'Nexmo',
                    cost: '$0.001/per msg',
                    status: 'Coming Soon!',
                    disabled: true,
                },
                {
                    'title': 'mGage',
                    cost: '$0.01/per msg',
                    status: 'Coming Soon!',
                    disabled: true,
                },
            ],
            providerInfo: [
                {
                    desc: 'AllCommerce\'s Built-In SMS Provider, needs no configuration and is always enabled. Assign it to your shop(s) to use it!',
                    fields: [],
                    price: '$0.01/per message (sending or receiving)'
                },
                {
                    desc: 'Enter the fields with the data related to using the Twilio API in your Account.',
                    fields: [
                        {
                            name: 'type',
                            desc: 'Phone Number Type',
                            type: 'select',
                            options: {phone: 'Phone', shortcode: 'ShortCode'},
                            value: ''
                        },
                        {
                            name: 'number',
                            desc: 'Phone Number or Short Code to Use',
                            type: 'text',
                            value: ''
                        },
                        {
                            name: 'authToken',
                            desc: 'Twilio API Token',
                            type: 'text',
                            value: ''
                        },
                        {
                            name: 'accountSid',
                            desc: 'Twilio Account ID',
                            type: 'text',
                            value: ''
                        },
                    ],
                    price: 'Free! (Requires Twilio Account)'
                }
            ],
            activeProviderInfo: ''
        };
    },
    computed: {
        ...mapGetters({
            getView: 'smsManager/getClientContentView'
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
        this.setAsideBar('aside-sms-manager-context-tab')
        this.setAsideTitle('SMS Manager - Account View')

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
