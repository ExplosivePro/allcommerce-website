<template>
    <account-summary-view
        :total="total"
        :subtotal="subtotal"
        :shipping="shipping"
        :tax-lines="getTaxLines"

        :line-items="itemPrices"
        :pricing="pricing"
        :tax-text="taxText"
        :shipping-text="shippingText"
        @gift-submit="enterGiftCode"
    ></account-summary-view>
</template>

<script>
    import AccountSummaryView from "../../screens/checkout/AccountSummaryComponent";

    import { mapActions, mapGetters, mapMutations } from 'vuex';

    export default {
        name: "AccountSummaryComponentContainer",
        components: {
            AccountSummaryView
        },
        props: [],
        watch: {
            itemPrices(prices) {
                console.log('woot, new itemPrices! ', prices)
            },
            subtotal(price) {
                console.log('woot, new subtotal! ', price)
                this.pricing.subTotal = price;
            },
            shippingReady(flag) {
                console.log('Shipping Ready Updated!', flag);
                this.updateShipTaxText();
            },
            lmLoading(flag) {
                if(this.shippingReady) {
                    console.log('Lead Manager Loading', flag);
                    this.updateShipTaxText();
                }
            },
            shippingRate(rate) {
                this.setShippingRate(rate)
            },
            getTaxLines(lines) {
                if(lines.length === 0) {
                    this.taxText = '$0.00'
                }
                else {
                    this.taxText = '';
                }
            }
        },
        data() {
            return {
                taxText: '<small>Shipping Address Required</small>',
                shippingText: '<small>Shipping Address Required</small>',
                pricing: {
                    shipping: 0.00,
                    subTotal: 0.00,
                    total: 0.00,
                }
            };
        },
        computed: {
            shipping() {
                if(this.shippingMethodsReady) {
                    return '$'+this.shippingRate;
                }
                else {
                    return this.shippingText;
                }
            },
            total() {
                let total = 0.00;

                if(this.getNewTotal > 0) {
                    total = this.getNewTotal;
                }

                return total;
            },
            ...mapGetters({
                'itemPrices': 'itemPrices',
                'subtotal': 'priceCalc/getSubTotal',
                'getNewTotal': 'priceCalc/getTotal',
                'getTaxLines': 'priceCalc/getTaxLines',
                'shippingReady': 'leadManager/shippingReady',
                'lmLoading': 'leadManager/loading',
                'shippingRate': 'shipping/selectedRate',
                'shippingMethodsReady': 'shipping/moduleReady'
            }),
        },
        methods: {
            enterGiftCode(code) {
                alert('You entered '+code);
            },
            updateShipTaxText() {
                if(this.shippingReady === true) {
                    if(this.lmLoading) {
                        this.taxText = '<small>Calculating Tax...</small>'
                        this.shippingText = '<small>Loading Shipping Methods...</small>'
                    }
                    else {
                        this.taxText = '<small>Shipping entered.</small>'
                        this.shippingText = '<small>Shipping entered.</small>'
                    }
                }
                else {
                    this.taxText = '<small>Shipping Address Required</small>'
                    this.shippingText = '<small>Shipping Address Required</small>'
                }
            },
            ...mapMutations({
                setShippingRate: 'priceCalc/shipping'
            })
        },
        mounted() {
            console.log('AccountSummaryComponentContainer mounted!', this.itemPrices)
        }
    }
</script>

<style scoped>

</style>
