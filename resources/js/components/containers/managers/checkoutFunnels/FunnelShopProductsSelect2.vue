<template>
    <funnel-shop-products
        :name="name"
        :ready="ready"
        :options="options"
        :loading="loading"
    ></funnel-shop-products>
</template>

<script>
    import FunnelShopProducts from "../../../presenters/managers/CheckoutFunnels/FunnelShopProducts";

    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: "FunnelShopProductsSelect2",
        components: {
            FunnelShopProducts
        },
        props: ['name'],
        watch: {
            getActiveShopUUID(uuid) {
                if(uuid === '') {
                    this.loading = false;
                    this.ready = false;
                }
                else {
                    this.loading = true;
                    this.fetchProducts();
                }
            },
            getShopProductOptions(options) {
                if(options === '') {
                    this.options = {};
                }
                else {
                    if(options.length === 0) {
                        this.ready = true;
                        this.options = [];
                    }
                    else {
                        this.options = options;
                        this.ready = true;
                    }
                }

                this.loading = false;
            }
        },
        data() {
            return {
                ready: false,
                options: {},
                loading: false
            }
        },
        computed: {
            ...mapGetters({
                getActiveShopUUID: 'checkoutFunnelsManager/activeShop',
                getShopProductOptions: 'checkoutFunnelsManager/shopProductOptions'
            }),
        },
        methods: {
            ...mapActions({
                fetchProducts: 'checkoutFunnelsManager/fetchProductsForActiveShop'
            }),
        },
        mounted() {}
    }
</script>

<style scoped>

</style>
