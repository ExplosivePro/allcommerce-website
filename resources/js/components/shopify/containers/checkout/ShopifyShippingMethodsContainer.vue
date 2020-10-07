<template>
    <shipping-methods-load-out
        :show-shipping-methods="moduleReady"
        :shipping-methods="availableMethods"
        @selected="setSelectedShipping"
    ></shipping-methods-load-out>
</template>

<script>
    import ShippingMethodsLoadOut from "../../screens/checkout/DefaultExperienceShippingMethodsScreen";
    import { mapGetters } from 'vuex';

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
            }
        },
        data() {
            return {
                showShippingMethods: false
            }
        },
        computed: {
            ...mapGetters({
                moduleReady: 'shipping/moduleReady',
                availableMethods: 'shipping/availableMethods'
            })
        },
        methods: {
            setSelectedShipping(idx) {
                let methods = this.availableMethods
                console.log('Updating state for ', methods[idx])
            }
        },
        mounted() {
            console.log('ShopifyShippingMethodsContainer mounted!')
        }
    }
</script>

<style scoped>

</style>
