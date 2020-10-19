<template>
    <manager-screen
        :shops="getMerchantShops"
        :gateways="availableProviders"
        :assignStatus="assignmentStatus"
        @gateway-triggered="toggleGatewayAssignToShop"
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
            installmentProviders: 'paymentGatewaysManager/installmentProviders',
            shopAssignedGateways: 'paymentGatewaysManager/getMerchantShopAssignedGateways',
            clientEnabledGateways: 'paymentGatewaysManager/getClientEnabledGateways',
            assignmentStatus: 'paymentGatewaysManager/assignmentStatus'
        }),
        availableProviders() {
            let credit = this.curateCreditGatewayData(this.creditProviders);
            let express = this.curateCreditGatewayData(this.expressProviders);
            let install = this.curateCreditGatewayData(this.installmentProviders);

            return {
                credit: credit, // gateways[typeData.slug]
                express: express,
                install: install,
            }
        }
    },
    methods: {
        curateCreditGatewayData(credit) {
            let results = [];

            for(let idx in credit) {
                let gate =  credit[idx];
                let temp = {
                    id: gate.id,
                    name: gate.name,
                }

                for(let idy in gate['gateway_attributes']) {
                    let attrs = gate['gateway_attributes'][idy];

                    switch(attrs.name) {
                        case 'Description':
                            temp['desc'] = attrs.misc[0];
                            break;

                        case 'Status':
                            temp['status'] = this.getAvailableCreditGatewayStatus(gate, attrs);
                            temp['assign'] = this.getCreditAssignSegmentLayout(gate, attrs)
                            break
                    }
                }
                results.push(temp);
            }

            return results;
        },
        getAvailableCreditGatewayStatus(gateway, statusRecord) {
            let results = [];

            console.log('gateway...', gateway);
            console.log('clientEnabledGateways', this.clientEnabledGateways)
            console.log('setShopStatus...', statusRecord);

            for(let shop_elem in this.getMerchantShops) {
                switch(statusRecord.value) {
                    case 'Available':
                        let temp = statusRecord.value;

                        // Add'l check before saying "Available
                        // If dry run, then it is "Not Assigned"
                        if(gateway.name === "Dry Run Test Gateway") {
                            temp = 'Not Assigned';

                            // need to check if the gateway is assigned
                            let shopGates = this.shopAssignedGateways[shop_elem]['gateways']['credit'];
                            for(let z in shopGates) {
                                if(gateway.id === shopGates[z]['payment_provider'].id) {
                                    temp = 'Assigned';
                                    break;
                                }
                            }

                            console.log('ShopGates', shopGates);
                        }
                        else {
                            // If not dry run, then if client enabled, say "not Assigned"
                            if(gateway.id in this.clientEnabledGateways['credit']) {
                                temp = 'Not Assigned';

                                // need to check if the gateway is assigned
                                let shopGates = this.shopAssignedGateways[shop_elem]['gateways']['credit'];
                                for(let z in shopGates) {
                                    if(gateway.id === shopGates[z]['payment_provider'].id) {
                                        temp = 'Assigned';
                                        break;
                                    }
                                }
                            }
                            else {
                                // If not dry run, then if client NOT enabled, say "Not Available"
                                temp = 'Not Available';
                                // @todo - need to check if the gateway is assigned
                                /*
                                let shopGates = this.shopAssignedGateways[0]['gateways']['credit'];
                                for(let z in shopGates) {
                                    if(gateway.id === shopGates[z]['payment_provider'].id) {
                                        temp = 'Assigned';
                                        break;
                                    }
                                }
                                */
                            }
                        }
                        results.push(temp);
                    break;

                    default:
                        results.push('Coming Soon!');
                }
            }

            return results;
        },
        getCreditAssignSegmentLayout(gateway, statusRecord) {
            let results = [];

            for(let shop_elem in this.getMerchantShops) {
                switch(statusRecord.value) {
                    case 'Available':
                        let temp = {
                            type: 'button',
                            markup: 'Assign',
                            action: 'assign'
                        }

                        // Add'l check before saying "Available
                        // If dry run, then it is "Not Assigned"
                        if(gateway.name === "Dry Run Test Gateway") {
                            temp = {
                                type: 'button',
                                markup: 'Assign',
                                action: 'assign'
                            };

                            // need to check if the gateway is assigned
                            //this.clientEnabledGateways['credit']
                            let shopGates = this.shopAssignedGateways[shop_elem]['gateways']['credit'];
                            console.log('ShopGates', shopGates);
                            for(let z in shopGates) {
                                if(gateway.id === shopGates[z]['payment_provider'].id) {
                                    temp = {
                                        type: 'button',
                                        markup: 'UnAssign',
                                        action: 'un-assign'
                                    };
                                    break;
                                }
                            }

                        }
                        else {
                            // If not dry run, then if client enabled, say "not Assigned"
                            if(gateway.id in this.clientEnabledGateways['credit']) {
                                temp = {
                                    type: 'button',
                                    markup: 'Assign',
                                    action: 'assign'
                                };

                                // check if dry run is assigned, cuz we can't assign anything when its enabled
                                for(let u in this.shopAssignedGateways[shop_elem].gateways['credit']) {
                                    let info = this.shopAssignedGateways[shop_elem].gateways['credit'][u];

                                    if(info['payment_provider'].name === "Dry Run Test Gateway") {
                                        temp =  {
                                            type: 'text',
                                            markup: '<p>UnAssign Dry Run First.</p>'
                                        };
                                        break;
                                    }
                                    else if(gateway.id === info['provider_uuid']) {
                                        temp = {
                                            type: 'button',
                                            markup: 'UnAssign',
                                            action: 'un-assign'
                                        };
                                        break;
                                    }
                                }
                            }
                            else {
                                // If not dry run, then if client NOT enabled, say "Not Available"
                                temp =  {
                                    type: 'text',
                                    markup: '<p>Not Assignable</p>'
                                };

                                // @todo - get the gateway type (credit, express, install)
                                // @todo - need to check if the gateway is assigned

                            }
                        }

                        results.push(temp);
                        break;
                    default:
                        results.push({
                            type: 'text',
                            markup: '<p>Not Assignable</p>'
                        });
                }
            }

            return results;
        },

        curateExpressGatewayData(credit) {
            let results = [];

            for(let idx in credit) {
                let gate =  credit[idx];
                let temp = {
                    name: gate.name,
                }

                for(let idy in gate['gateway_attributes']) {
                    let attrs = gate['gateway_attributes'][idy];

                    switch(attrs.name) {
                        case 'Description':
                            temp['desc'] = attrs.misc[0];
                            break;

                        case 'Status':
                            temp['status'] = this.getAvailableExpressGatewayStatus(gate, attrs);
                            temp['assign'] = this.getExpressAssignSegmentLayout(gate, attrs)
                            break
                    }
                }
                results.push(temp);
            }

            return results;
        },
        getAvailableExpressGatewayStatus(gateway, statusRecord) {
            let results = [];

            console.log('gateway...', gateway);
            console.log('clientEnabledGateways', this.clientEnabledGateways)
            console.log('setShopStatus...', statusRecord);

            for(let shop_elem in this.clientEnabledGateways) {
                switch(statusRecord.value) {
                    case 'Available':
                        let temp = statusRecord.value;

                        // Add'l check before saying "Available
                        // If dry run, then it is "Not Assigned"
                        if(gateway.name === "Dry Run Test Gateway") {
                            temp = 'Not Assigned';

                            // need to check if the gateway is assigned
                            let shopGates = this.shopAssignedGateways[0]['gateways']['credit'];
                            for(let z in shopGates) {
                                if(gateway.id === shopGates[z]['payment_provider'].id) {
                                    temp = 'Assigned';
                                    break;
                                }
                            }

                            console.log('ShopGates', shopGates);
                        }
                        else {
                            // If not dry run, then if client enabled, say "not Assigned"
                            if(gateway.id in this.clientEnabledGateways[shop_elem]) {
                                temp = 'Not Assigned';
                            }
                            else {
                                // If not dry run, then if client NOT enabled, say "Not Available"
                                temp = 'Not Available';

                                // @todo - get the gateway type (credit, express, install)
                                // @todo - need to check if the gateway is assigned
                                /*
                                let shopGates = this.shopAssignedGateways[0]['gateways']['credit'];
                                for(let z in shopGates) {
                                    if(gateway.id === shopGates[z]['payment_provider'].id) {
                                        temp = 'Assigned';
                                        break;
                                    }
                                }
                                */
                            }
                        }
                        results.push(temp);
                        break;

                    default:
                        results.push('Coming Soon!');
                }
            }

            return results;
        },
        getExpressAssignSegmentLayout(gateway, statusRecord) {
            let results = [];

            for(let shop_elem in this.clientEnabledGateways) {
                switch(statusRecord.value) {
                    default:
                        results.push({
                            type: 'text',
                            markup: '<p>Not Assignable</p>'
                        });
                }
            }

            return results;
        },

        curateInstallGatewayData(credit) {
            let results = [];

            for(let idx in credit) {
                let gate =  credit[idx];
                let temp = {
                    name: gate.name,
                }

                for(let idy in gate['gateway_attributes']) {
                    let attrs = gate['gateway_attributes'][idy];

                    switch(attrs.name) {
                        case 'Description':
                            temp['desc'] = attrs.misc[0];
                            break;

                        case 'Status':
                            temp['status'] = this.getAvailableInstallGatewayStatus(gate, attrs, idx);
                            temp['assign'] = this.getInstallAssignSegmentLayout(gate, attrs)
                            break
                    }
                }
                results.push(temp);
            }

            return results;
        },
        getAvailableInstallGatewayStatus(gateway, statusRecord) {
            let results = [];

            console.log('gateway...', gateway);
            console.log('clientEnabledGateways', this.clientEnabledGateways)
            console.log('setShopStatus...', statusRecord);

            for(let shop_elem in this.clientEnabledGateways) {
                switch(statusRecord.value) {
                    case 'Available':
                        let temp = statusRecord.value;

                        // Add'l check before saying "Available
                        // If dry run, then it is "Not Assigned"
                        if(gateway.name === "Dry Run Test Gateway") {
                            temp = 'Not Assigned';

                            // need to check if the gateway is assigned
                            let shopGates = this.shopAssignedGateways[0]['gateways']['credit'];
                            for(let z in shopGates) {
                                if(gateway.id === shopGates[z]['payment_provider'].id) {
                                    temp = 'Assigned';
                                    break;
                                }
                            }

                            console.log('ShopGates', shopGates);
                        }
                        else {
                            // If not dry run, then if client enabled, say "not Assigned"
                            if(gateway.id in this.clientEnabledGateways[shop_elem]) {
                                temp = 'Not Assigned';
                            }
                            else {
                                // If not dry run, then if client NOT enabled, say "Not Available"
                                temp = 'Not Available';

                                // @todo - get the gateway type (credit, express, install)
                                // @todo - need to check if the gateway is assigned
                                /*
                                let shopGates = this.shopAssignedGateways[0]['gateways']['credit'];
                                for(let z in shopGates) {
                                    if(gateway.id === shopGates[z]['payment_provider'].id) {
                                        temp = 'Assigned';
                                        break;
                                    }
                                }
                                */
                            }
                        }
                        results.push(temp);
                        break;

                    default:
                        results.push('Coming Soon!');
                }
            }

            return results;
        },
        getInstallAssignSegmentLayout(gateway, statusRecord) {
            let results = [];

            for(let shop_elem in this.clientEnabledGateways) {
                switch(statusRecord.value) {
                    case 'Available':
                        let temp = {
                            type: 'button',
                            markup: 'Assign',
                            action: 'assign'
                        }

                        // Add'l check before saying "Available
                        // If dry run, then it is "Not Assigned"
                        if(gateway.name === "Dry Run Test Gateway") {
                            temp = {
                                type: 'button',
                                markup: 'Assign',
                                action: 'assign'
                            };

                            // need to check if the gateway is assigned
                            let shopGates = this.shopAssignedGateways[0]['gateways']['credit'];
                            for(let z in shopGates) {
                                if(gateway.id === shopGates[z]['payment_provider'].id) {
                                    temp = {
                                        type: 'button',
                                        markup: 'UnAssign',
                                        action: 'un-assign'
                                    };
                                    break;
                                }
                            }

                            console.log('ShopGates', shopGates);
                        }
                        else {
                            // If not dry run, then if client enabled, say "not Assigned"
                            if(gateway.id in this.clientEnabledGateways[shop_elem]) {
                                temp = {
                                    type: 'button',
                                    markup: 'Assign',
                                    action: 'assign'
                                };
                            }
                            else {
                                // If not dry run, then if client NOT enabled, say "Not Available"
                                temp =  results.push({
                                    type: 'text',
                                    markup: '<p>Not Assignable</p>'
                                });;

                                // @todo - get the gateway type (credit, express, install)
                                // @todo - need to check if the gateway is assigned

                            }
                        }
                        results.push(temp);
                        break;
                    default:
                        results.push({
                            type: 'text',
                            markup: '<p>Not Assignable</p>'
                        });
                }
            }

            return results;
        },

        toggleGatewayAssignToShop(details) {
            let action = details.action;
            let gateway = details.gateway;
            let shop = details.merchantShop;
            let type = details.shopType;

            switch(action) {
                case 'assign':
                    this.assignGateway({
                        gateway: gateway,
                        shop: shop,
                        type: type
                    })
                    break;

                case 'un-assign':
                    this.unAssignGateway({
                        gateway: gateway,
                        shop: shop,
                        type: type
                    })
                    break;

                default:
                    console.log('Unsupported paymentType - '+ type);
            }
            /**
             * Steps
             * 4. Do a logic that slides up the view and shows loading (and maybe tried to slideItdown).
             * 5. Watch and update the necessary data
             * 6. Slide up the loading and slideback down the view
             * @todo - redo the steps to support assigning a shop
             */
        },
        ...mapActions({
            setAsideBar: 'asidebar/setContextTabActiveComponent',
            assignGateway: 'paymentGatewaysManager/assignGatewayToShop',
            unAssignGateway: 'paymentGatewaysManager/unAssignGatewayToShop',

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
