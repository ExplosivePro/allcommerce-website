<template>
    <account-dashboard
        :shop="shop"
        :hmac="hmac"
        :funnel="funnel"
        :inventory="inventory"
    ></account-dashboard>
</template>

<script>

    export default {
        name: "accountDashboardContainer",
        components: {  },
        props: ['shop', 'inventory', 'hmac', 'funnel','apiKey', 'redirectUri'],
        data() {
            return {
                ready: false
            };
        },
        computed: {

        },
        methods: {

        },
        mounted() {
            console.log('Shopify Account Dashboard Container Mounted!');
        }
    }
</script>

<style>
    @media screen {

    }

    @media screen and (max-width: 999px) {

    }

    @media screen and (min-width: 1000px) {

    }
</style>

<style scoped>
    @media screen {

    }

    @media screen and (max-width: 999px) {

    }

    @media screen and (min-width: 1000px) {

    }
</style>
