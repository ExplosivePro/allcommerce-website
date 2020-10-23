<template>
    <checkout-experience
        :loading="loading"
        :loading-msg="loadingMsg"
        :countries="countyDrop"
        :state-drops="stateDrops"
        :show-one-click="showOneClick"
        :prefill-data="billShipPreFill"
        @updated="updateCheckoutState"
        @submit-payment="submitPayment"
    ></checkout-experience>
</template>

<script>
    import CheckoutExperience from "../../screens/checkout/DefaultExperience";

    import { mapActions, mapState, mapGetters, mapMutations } from 'vuex';

    export default {
        name: "DefaultCheckoutExperienceContainer",
        components: {
            CheckoutExperience
        },
        props: ['items', 'shippingMethods', 'shopId', 'checkoutType', 'checkoutId', 'apiUrl', 'devMode', 'gateways'],
        watch: {
            devMode(flag) {
                console.log('Oh boy, devMode = '+flag);
            },
            showOneClick(flag) {
                console.log('Oh boy, showOneClick = '+flag);
            },
            email(addy) {
                console.log('Email Address was updated to '+addy);
                this.updateLeadEmail();
            },
            lmLeadUuid(uuid) {
                console.log('leadManager reporting Lead UUID was updated to '+uuid+' updating the store.');
                this.setLeadUuid(uuid);
            },
            shippingReady(flag) {
                console.log('ShippingReady changed to '+flag);
                let ready = (this.emailReady && this.shippingReady);
                this.setPostageReady(ready);
            },
            emailReady(flag) {
                console.log('EmailReady changed to '+flag);
                let ready = (this.emailReady && this.shippingReady);
                this.setPostageReady(ready);
            },
            oneClickReady(flag) {
                console.log('oneClickReady changed to '+flag, this.oneClickData);

                this.toggleOneClickMode(flag);
                this.setClickData(this.oneClickData);
            },
            oneClickResults(data) {
                this.billShipSame = false;
                this.billShipPreFill = data;
            },
            paymentLoading(flag) {
                if(flag) {
                    console.log('Payment initiating! Shhhhh...');
                    this.loadingMsg = 'Submitting Payment! One Moment Please..'
                }
                else {
                    console.log('Payment done.')
                    this.loadingMsg = 'Loading Your Secure Checkout!';
                }

                this.setLoading(flag)
            }
        },
        data() {
            return {
                billShipSame: true,
                billShipPreFill: '',
                loadingMsg: 'Loading Your Secure Checkout!'
            };
        },
        computed: {
            ...mapGetters({
                loading: 'loading',
                paymentLoading: 'checkoutGatewayManager/loading',
                email: 'customerEmail',
                lmLeadUuid: 'leadManager/leadUuid',
                emailReady: 'leadManager/emailReady',
                shippingReady: 'leadManager/shippingReady',
                billingReady: 'leadManager/billingReady',
                postageReady: 'postageReady',
                taxReady: 'taxReady',
                countyDrop: 'geography/getCountries',
                stateDrops: 'geography/getStates',
                showOneClick: 'oneClickManager/active',
                oneClickReady: 'leadManager/oneClickReady',
                oneClickData: 'leadManager/oneClickData',
                oneClickResults: 'oneClickManager/oneClickResults'
            }),
        },
        methods: {
            ...mapMutations({
                setLoading: 'loading',
                setApiUrl: 'backendUrl',
                setShop: 'shopUuid',
                setLeadUuid: 'leadUuid',
                updateEmail: 'customerEmail',
                updateEmailList: 'optInMailing',
                setShipping: 'leadManager/shippingAddress',
                setBilling: 'leadManager/billingAddress',
                setShippingMethods: 'shipping/availableMethods',
                setClickData: 'oneClickManager/clickData',
            }),
            ...mapActions({
                initCart: 'initCart',
                configCheckout: 'configCheckout',
                updateLeadEmail: 'updateLeadEmail',
                setShippingReady: 'setShippingReady',
                setBillingReady: 'setBillingReady',
                setPostageReady: 'setPostageReady',
                toggleOneClickMode: 'oneClickManager/toggleOneClickMode',
                initCheckoutGatewayModules: 'initCheckoutGatewayModules',
                executeCreditAuth: 'checkoutGatewayManager/executeCreditAuth'
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

                        case 'shippingFirst':
                        case 'shippingLast':
                        case 'shippingCompany':
                        case 'shippingAddress':
                        case 'shippingApt':
                        case 'shippingCity':
                        case 'shippingZip':
                        case 'shippingCountry':
                        case 'shippingState':
                        case 'shippingPhone':
                            this.setShipping(payload);

                            if(this.shippingReady) {
                                this.setShippingReady(true);
                            }
                            break;

                        case 'billingFirst':
                        case 'billingLast':
                        case 'billingCompany':
                        case 'billingAddress':
                        case 'billingApt':
                        case 'billingZip':
                        case 'billingCity':
                        case 'billingCountry':
                        case 'billingState':
                        case 'billingPhone':
                            this.setBilling(payload);

                            if(this.billingReady) {
                                this.setBillingReady(true);
                            }
                            break;

                        case 'billingShippingSame':
                            this.billShipSame = payload.value;

                            if((!this.billShipSame)) {
                                this.setBillingReady(false);
                            }
                            break;

                        case 'billingValid':
                            if((!this.billShipSame)) {
                                this.setBillingReady(payload.value);
                            }
                            break;

                        case 'shippingValid':
                            this.setShippingReady(payload.value);
                            break;

                        default:
                            console.log('Invalid Checkout State method - ', payload.method);
                    }
                }
                else {
                    console.log('Invalid entry - ', payload)
                }
            },
            submitPayment(payload) {
                switch(payload.type) {
                    case 'credit':
                        // @todo - all sorts of crazyness
                        this.executeCreditAuth(payload);

                        break;
                    default:
                        alert(`Unsupported Payment Type - ${payload.type}!`)
                }
            }
        },
        mounted() {
            this.setApiUrl(this.apiUrl);
            this.configCheckout({type:this.checkoutType, id:this.checkoutId});
            this.setShop(this.shopId);
            this.initCart(this.items);
            this.setShippingMethods(this.shippingMethods);
            this.initCheckoutGatewayModules(this.gateways);

            setTimeout(() => this.setLoading(false), 1250);
            console.log('DefaultCheckoutExperienceContainer mounted!', this.items);
        }
    }
</script>

<style scoped>

</style>
