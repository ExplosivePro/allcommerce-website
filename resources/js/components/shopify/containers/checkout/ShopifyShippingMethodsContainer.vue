<template>
    <shipping-methods-load-out
        :show-shipping-methods="showShippingMethods"
        :shipping-methods="availableMethods"
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
        methods: {},
        mounted() {
            console.log('ShopifyShippingMethodsContainer mounted!')
        }
    }
</script>

<style scoped>

</style>
