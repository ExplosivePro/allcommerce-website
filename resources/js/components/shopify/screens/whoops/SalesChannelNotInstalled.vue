<template>
    <polaris-empty-state
        heading="Hey, you're back!"
        image="https://allcommerce.s3-us-west-1.amazonaws.com/assets/doggygif.gif">
        <p>Our records show you may have removed the app from your account. Reinstalling should fix that.</p>
    </polaris-empty-state>
</template>

<script>
    export default {
        name: "SalesChannelNotInstalled"
    }
</script>

<style>
    @media screen {
        .Polaris-EmptyState {
            margin-top: 0;
            padding: 0;
        }
    }

    @media screen and (max-width: 999px) {
        .py-4 {
            margin: 0 5%;
        }

        .Polaris-EmptyState__Section {
            margin-top: 5%;
        }

        .Polaris-EmptyState__ImageContainer {
            margin-top: 10%;
        }
    }

    @media screen and (min-width: 1000px) {
        .py-4 {
            margin: 0 10%;
        }

        .Polaris-EmptyState__Section {
            margin-top: 5%;
        }
    }
</style>
