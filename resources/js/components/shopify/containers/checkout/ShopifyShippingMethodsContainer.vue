<template>
    <shipping-methods-load-out
        :show-shipping-methods="moduleReady"
        :shipping-methods="curatedAvailableMethods"
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
            },
            availableMethods(methods) {
                this.curateAvailableMethods(methods);
            }
        },
        data() {
            return {
                showShippingMethods: false,
                selectedIdx: 0,
                curatedAvailableMethods: []
            }
        },
        computed: {
            ...mapGetters({
                lmLoading: 'leadManager/loading',
                taxReady: 'taxReady',
                moduleReady: 'shipping/moduleReady',
                availableMethods: 'shipping/availableMethods',
                subtotal: 'priceCalc/getSubTotal'
            })
        },
        methods: {
            ...mapMutations({
                setShippingPrice: 'shipping/selectedRate'
            }),
            ...mapActions({
                ajaxDraftOrderFromShippingMethod: 'ajaxDraftOrderFromShippingMethod'
            }),
            curateAvailableMethods(methods) {
                for(let method in methods) {
                    let subtotal = this.subtotal;
                    let maxPrice = methods[method]['max_price'];
                    let minPrice = methods[method]['min_price'];
                    // if (minPrice is NULL || subtotal > minPrice) && (maxPrice is NULL || subTotal < maxPrice) enable
                    methods[method]['disabled'] = true;
                    if((minPrice === null) || (subtotal > minPrice)) {
                        if((maxPrice === null) || (subtotal < maxPrice)) {
                            methods[method]['disabled'] = false;
                        }
                    }
                }

                this.curatedAvailableMethods = methods;
            },
            setSelectedShipping(idx) {
                this.selectedIdx = idx;
                let methods = this.availableMethods
                console.log('Updating state for ', methods[idx])
                this.setShippingPrice(methods[idx].price)
                this.ajaxDraftOrderFromShippingMethod(methods[idx]);
            }
        },
        mounted() {
            this.curateAvailableMethods(this.availableMethods);
            console.log('ShopifyShippingMethodsContainer mounted!')
        }
    }
</script>

<style scoped>

</style>
