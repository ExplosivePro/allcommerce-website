<template>
    <checkout-experience
        :has-timer="timerSegment"
        :has-express-checkout="expressCheckout"
        :line-items="items"
        :loading="globalLoading"
        :disable-fields="loading"
        :countries="countyDrop"
        :state-drops="stateDrops"
        :shipping-line="calculateShipping"
        :pricing="pricing"
        :tax="renderTax"
        @updated="updateCheckoutState"
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
            shippingReady(flag) {
                console.log('ShippingReady changed to '+flag);
            },
            shipping(price) {
                console.log('Active shipping price set to - $'+price);
                this.setPriceShip(price);
                this.setShowShipping(true);
                this.updateTotal();
            },
        },
        data() {
            return {
                timerSegment: false,
                expressCheckout: false,
                pricing: {
                    subTotal: 10.00,
                    total: 0.00
                },
                tax: []
            };
        },
        computed: {
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
            ...mapState('geography', {
                countyDrop: 'countries',
                stateDrops: 'states'
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

                    /*
                    results = '';

                    for(int x in this.taxLine.tax_lines) {
                        results = results + `</p></div><div class="inner-subtotal-row">`
                        val = this.taxLine.tax_lines[x];
                        results = results + `<p>${val.title}</p>`;
                        results = results + `<p>${val.price}`;
                    }
                     */
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
        },
        methods: {
            ...mapMutations({
                setTotalTax: 'priceCalc/tax',
                setPriceShip: 'priceCalc/shipping',
                setShowShipping: 'leadManager/showShipping'
            }),
            ...mapActions({
                setShop: 'setShopUuid',
                setApiUrl: 'leadManager/setApiUrl',
                setLeadUuid: 'setLeadUuid',
                initCart: 'initCart',
                initShipping: 'initShipping',
                configCheckout: 'configCheckout',
                updateEmail: 'updateEmail',
                updateEmailList: 'updateEmailList',
                setGlobalLoading: 'setLoading',
                updateTotal: 'priceCalc/calculateTotal',
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
