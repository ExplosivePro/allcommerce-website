<template>
    <polaris-empty-state
        heading="Wait. Who are you?"
        image="https://allcommerce.s3-us-west-1.amazonaws.com/assets/no-access-polaris.png">
        <p>We could not find you in our system. You may need to un-install and then re-install. Sorry about that.</p>
    </polaris-empty-state>
</template>

<script>
    export default {
        name: "AccountDoesNotExistScreen"
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
