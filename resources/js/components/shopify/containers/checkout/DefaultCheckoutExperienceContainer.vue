<template>
    <checkout-experience
        :has-timer="timerSegment"
        :has-express-checkout="expressCheckout"
        :line-items="items"
        :loading="globalLoading"
        :disable-fields="loading"
        :countries="countyDrop"
        :state-drops="stateDrops"
        @updated="updateCheckoutState"
    ></checkout-experience>
</template>

<script>
    import CheckoutExperience from "../../screens/checkout/DefaultExperience";

    import { mapActions, mapState } from 'vuex';

    export default {
        name: "DefaultCheckoutExperienceContainer",
        components: {
            CheckoutExperience
        },
        props: ['items', 'shopId', 'checkoutType', 'checkoutId', 'apiUrl'],
        watch: {
            leadLoading(flag) {
                console.log('LeadLoading set to - '+flag)
            },
            leadUuid(uuid) {
                console.log('LeadUuid set to - '+uuid);
                this.setLeadUuid(uuid);
            },
            shippingValidated(flag) {
                console.log('shippingValidated set to - '+flag)
                if(flag) {
                    this.updateBillingShipping()
                }
            },
            billingValidated(flag) {
                console.log('billingValidated set to - '+flag)
                if(flag) {
                    this.updateBillingShipping()
                }
            },
        },
        data() {
            return {
                timerSegment: false,
                expressCheckout: false
            };
        },
        computed: {
            ...mapState({
                email: 'email',
                cart: 'cart',
                globalLoading: 'loading'
            }),
            ...mapState('leadManager', {
                leadLoading: 'loading',
                leadUuid: 'leadUuid',
                shippingValidated: 'shippingValidated',
                billingValidated: 'billingValidated',
            }),
            ...mapState('geography', {
                countyDrop: 'countries',
                stateDrops: 'states'
            }),
            loading() {
                let results = false;

                if(this.globalLoading) {
                    results = true;
                }
                else {
                    results = (
                        this.leadLoading == true
                    );
                }

                return results;
            }
        },
        methods: {
            ...mapActions({
                setShop: 'setShopUuid',
                setApiUrl: 'leadManager/setApiUrl',
                setLeadUuid: 'setLeadUuid',
                initCart: 'initCart',
                configCheckout: 'configCheckout',
                updateEmail: 'updateEmail',
                updateEmailList: 'updateEmailList',
                setGlobalLoading: 'setLoading',
                updateShippingAddress: 'leadManager/updateShippingAddress',
                updateBillingAddress: 'leadManager/updateBillingAddress',
                updateBillingShipping: 'updateBillingShipping',
                setBillingShippingSame: 'leadManager/setBillingShippingSame',
            }),
            updateCheckoutState(payload) {
                if('method' in payload) {
                    console.log('Receiving checkout state update - ', payload);

                    switch(payload.method) {
                        case 'email':
                            this.updateEmail(payload.value);
                            break;

                        case 'emailList':
                            this.updateEmailList(payload.value);
                            break;

                        case 'billingShippingSame':
                            this.setBillingShippingSame(payload.value);
                            break;

                        case 'shippingFirst':
                        case 'shippingLast':
                        case 'shippingCompany':
                        case 'shippingAddress':
                        case 'shippingApt':
                        case 'shippingCity':
                        case 'shippingCountry':
                        case 'shippingState':
                        case 'shippingZip':
                        case 'shippingPhone':
                            this.updateShippingAddress({col: payload.method, val:payload.value});
                            this.updateBillingShipping()
                            break;

                        case 'billingFirst':
                        case 'billingLast':
                        case 'billingCompany':
                        case 'billingAddress':
                        case 'billingApt':
                        case 'billingCity':
                        case 'billingCountry':
                        case 'billingState':
                        case 'billingZip':
                        case 'billingPhone':
                            this.updateBillingAddress({col: payload.method, val:payload.value});
                            this.updateBillingShipping()
                            break;

                        default:
                            console.log('Invalid Checkout State method - ', payload.method);
                    }
                }
                else {
                    console.log('Invalid entry - ', payload)
                }
            }
        },
        mounted() {
            this.setApiUrl(this.apiUrl);
            this.configCheckout({type:this.checkoutType, id:this.checkoutId});
            this.setShop(this.shopId);
            this.initCart(this.items);

            setTimeout(() => this.setGlobalLoading(false), 1500);

            console.log('DefaultCheckoutExperienceContainer mounted!', this.items);
        }
    }
</script>

<style scoped>

</style>
