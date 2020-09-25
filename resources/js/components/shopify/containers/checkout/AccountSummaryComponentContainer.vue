<template>
    <account-summary-view
        :line-items="itemPrices"
        :pricing="pricing"
        :tax-text="taxText"
        :shipping-text="shippingText"
        @gift-submit="enterGiftCode"
    ></account-summary-view>
</template>

<script>
    import AccountSummaryView from "../../screens/checkout/AccountSummaryComponent";

    import { mapActions, mapGetters } from 'vuex';

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
            }
        },
        data() {
            return {
                taxText: '<small>Shipping Address Required</small>',
                shippingText: '<small>Shipping Address Required</small>',
                pricing: {
                    subTotal: 0.00,
                    total: 0.00,
                }
            };
        },
        computed: {
            ...mapGetters({
               'itemPrices': 'itemPrices',
                'subtotal': 'priceCalc/getSubTotal',
                'total': 'priceCalc/getTotal',
                'shippingReady': 'leadManager/shippingReady',
                'lmLoading': 'leadManager/loading',
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
            }
        },
        mounted() {
            this.pricing.subTotal = this.subtotal;
            this.pricing.total = this.total;
            console.log('AccountSummaryComponentContainer mounted!', this.itemPrices)
        }
    }
</script>

<style scoped>

</style>
