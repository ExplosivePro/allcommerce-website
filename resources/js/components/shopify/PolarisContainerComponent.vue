<template>
    <div class="sales-channel-content">
        <div class="inner-content">
            <h1> Heyo! </h1>
        </div>
    </div>
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
        .sales-channel-content {
            width: 100%;
            height: 100%;
        }

        .inner-content {
            display: flex;
            flex-flow: column;
            height: 100%;
            justify-content: center;
            align-items: center;
        }

        .Polaris-EmptyState {
            height: 100%;
        }


    }

    @media screen and (max-width: 999px) {

    }

    @media screen and (min-width: 1000px) {

    }
</style>
