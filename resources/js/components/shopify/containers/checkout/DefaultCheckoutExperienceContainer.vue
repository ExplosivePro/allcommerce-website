<template>
    <checkout-experience
        :loading="loading"
        :countries="countyDrop"
        :state-drops="stateDrops"
        @updated="updateCheckoutState"
    ></checkout-experience>

    <!--
    <checkout-experience
        :has-timer="timerSegment"
        :has-express-checkout="expressCheckout"
        :line-items="items"
        :disable-fields="loading"
        :shipping-line="calculateShipping"
        :pricing="pricing"
        :tax="renderTax"
    ></checkout-experience>
    -->
</template>

<script>
    import CheckoutExperience from "../../screens/checkout/DefaultExperience";

    import { mapActions, mapState, mapGetters, mapMutations } from 'vuex';

    export default {
        name: "DefaultCheckoutExperienceContainer",
        components: {
            CheckoutExperience
        },
        props: ['items', 'shippingMethods', 'shopId', 'checkoutType', 'checkoutId', 'apiUrl', 'devMode'],
        watch: {
            devMode(flag) {
                console.log('Oh boy, devMode = '+flag);
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
            /*
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
            purchaseSubtotal(amt) {
                console.log('subTotal set to - '+amt);
                this.pricing.subTotal = amt;
            },
            total(amt) {
                console.log('total set to - '+amt);
                this.pricing.total = amt;
            },
            taxLine(lines) {
                console.log('Incoming tax line(s) - ', lines);

                this.setTotalTax(lines.total);
                this.updateTotal();
            },
            shippingLine(lines) {
                console.log('Incoming shipping line(s) - ', lines);
                this.initShipping(lines);
            },

            shipping(price) {
                console.log('Active shipping price set to - $'+price);
                this.setPriceShip(price);
                this.setShowShipping(true);
                this.updateTotal();
            },
            */
        },
        data() {
            return {
                billShipSame: true
                /*
                timerSegment: false,
                expressCheckout: false,
                pricing: {
                    subTotal: 10.00,
                    total: 0.00
                },
                tax: []
                */
            };
        },
        computed: {
            ...mapGetters({
                loading: 'loading',
                email: 'customerEmail',
                lmLeadUuid: 'leadManager/leadUuid',
                emailReady: 'leadManager/emailReady',
                shippingReady: 'leadManager/shippingReady',
                billingReady: 'leadManager/billingReady',
                postageReady: 'postageReady',
                countyDrop: 'geography/getCountries',
                stateDrops: 'geography/getStates'
            }),
            /*
            ...mapState({
                email: 'email',
                cart: 'cart',
                globalLoading: 'loading',
            }),
            ...mapGetters({
                shippingAmt: 'shippingAmt',
                getSubTotal: 'getSubTotal'
            }),
            ...mapState('leadManager', {
                leadLoading: 'loading',
                leadUuid: 'leadUuid',
                shippingValidated: 'shippingValidated',
                billingValidated: 'billingValidated',
                taxLine: 'taxLine',
                shippingLine: 'shippingLine',
                shippingReady: 'shippingReady',
                showShipping: 'showShipping'
            }),
            ...mapState('priceCalc', {
                purchaseSubtotal: 'subtotal',
                total: 'total'
            }),
            ...mapState('shipping', {
                shipping: 'shipping',
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
            },
            renderTax() {
                let results = `<small>Shipping Address Required</small>`;

                if(this.taxLine !== '') {
                    if(this.taxLine['tax_lines'].length == 0) {
                        results = `$${this.taxLine.total}`
                    }
                    else
                    {
                        results = this.taxLine
                    }
                }

                return results;
            },
            calculateShipping() {
                let results = `<small>Shipping Address Required</small>`;

                if(this.shippingReady === true) {
                    //results = `<small>Select Shipping Option.</small>`;
                    results = `<small>Loading Shipping Rates... <i class="fad fa-spinner-third fa-spin"></i></small>`;

                    if(this.showShipping) {
                        results = this.shipping;
                    }
                }
                else if(this.shippingReady === -1) {
                    let results = `<small>Error</small>`;
                }

                return results;
            },
            */
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
                setShippingMethods: 'shipping/availableMethods'
            }),
            ...mapActions({
                initCart: 'initCart',
                configCheckout: 'configCheckout',
                updateLeadEmail: 'updateLeadEmail',
                setShippingReady: 'setShippingReady',
                setBillingReady: 'setBillingReady',
                setPostageReady: 'setPostageReady',
            }),
            /*
            ...mapMutations({
                setTotalTax: 'priceCalc/tax',
                setPriceShip: 'priceCalc/shipping',
                setShowShipping: 'leadManager/showShipping'
            }),
            ...mapActions({
                updateTotal: 'priceCalc/calculateTotal',
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
            */
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
        },
        mounted() {
            this.setApiUrl(this.apiUrl);
            this.configCheckout({type:this.checkoutType, id:this.checkoutId});
            this.setShop(this.shopId);
            this.initCart(this.items);
            this.setShippingMethods(this.shippingMethods)

            setTimeout(() => this.setLoading(false), 1500);
            console.log('DefaultCheckoutExperienceContainer mounted!', this.items);
        }
    }
</script>

<style scoped>

</style>
