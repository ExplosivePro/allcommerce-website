<template>
    <polaris-layout-annotated-section
        title="Want to Start Selling Right Away?">
        <template slot="description">
            <p>We set you up with a starter checkout funnel with the first item made available to us with a URL you can place anywhere! The link can be on Shopify or anywhere you control that can host a weblink!</p>
            <polaris-button outline @click="goToUrl()">
                Try it out!
            </polaris-button>
        </template>
        <polaris-card title="Baby's First Checkout Funnel" sectioned>
            <polaris-description-list
                :items="items"
            ></polaris-description-list>
        </polaris-card>
    </polaris-layout-annotated-section>
</template>

<script>
    export default {
        name: "firstFunnelWidget",
        props: ['items']
    }
</script>

<style scoped>

</style>
