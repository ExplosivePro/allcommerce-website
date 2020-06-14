<template>
    <account-dashboard
        :shop="shop"
    ></account-dashboard>
</template>

<script>

    export default {
        name: "PolarisContainerComponent",
        components: {  },
        props: ['shop', 'apiKey', 'redirectUri'],
        data() {
            return {
                ready: false,
            };
        },
        computed: {

        },
        methods: {

        },
        async mounted() {

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
