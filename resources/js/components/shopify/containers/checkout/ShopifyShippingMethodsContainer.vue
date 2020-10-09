<template>
    <shipping-methods-load-out
        :show-shipping-methods="moduleReady"
        :shipping-methods="availableMethods"
        @selected="setSelectedShipping"
    ></shipping-methods-load-out>
</template>

<script>
    import ShippingMethodsLoadOut from "../../screens/checkout/DefaultExperienceShippingMethodsScreen";
    import { mapActions, mapGetters, mapMutations } from 'vuex';

    export default {
        name: "ShopifyShippingMethodsContainer",
        components: {
            ShippingMethodsLoadOut
        },
        props: [],
        watch: {
            moduleReady(flag) {
                console.log('ShopifyShippingMethodsContainer moduleReady - ' + flag)
                this.showShippingMethods = flag;
            },
            lmLoading(flag) {
                if(!flag && this.moduleReady && this.taxReady) {
                    this.setSelectedShipping(this.selectedIdx);
                }
            }
        },
        data() {
            return {
                showShippingMethods: false,
                selectedIdx: 0,
            }
        },
        computed: {
            ...mapGetters({
                lmLoading: 'leadManager/loading',
                taxReady: 'taxReady',
                moduleReady: 'shipping/moduleReady',
                availableMethods: 'shipping/availableMethods'
            })
        },
        methods: {
            ...mapMutations({
                setShippingPrice: 'shipping/selectedRate'
            }),
            ...mapActions({
                ajaxDraftOrderFromShippingMethod: 'ajaxDraftOrderFromShippingMethod'
            }),
            setSelectedShipping(idx) {
                this.selectedIdx = idx;
                let methods = this.availableMethods
                console.log('Updating state for ', methods[idx])
                this.setShippingPrice(methods[idx].price)
                this.ajaxDraftOrderFromShippingMethod(methods[idx]);
            }
        },
        mounted() {
            console.log('ShopifyShippingMethodsContainer mounted!')
        }
    }
</script>

<style scoped>

</style>
