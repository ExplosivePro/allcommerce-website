<template>
    <div class="sales-channel-content">
        <div class="inner-content">
            <polaris-display-text element="h1" size="large">
                Welcome to AllCommerce
            </polaris-display-text>
            <div class="subtitle">
                <polaris-display-text size="small" :class="'title-subtitle'">
                    Lets Get Started!
                </polaris-display-text>
            </div>

            <polaris-layout>
                <polaris-layout-annotated-section
                    title="Publish Your Inventory!"
                    description="Publish your items on AllCommerce in order to make Checkout Funnels">
                    <polaris-card title="Order details" sectioned>
                        <p>View a summary of your order.</p>
                    </polaris-card>
                </polaris-layout-annotated-section>

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
                            :items="generateItems"
                        ></polaris-description-list>
                    </polaris-card>
                </polaris-layout-annotated-section>
            </polaris-layout>
        </div>
    </div>
</template>

<script>
    export default {
        name: "accountDashboardComponent",
        props: ['shop'],
        computed: {
            generateItems() {
                return [
                    {
                        term: 'Product',
                        description: 'Placeholder Product'
                    },
                    {
                        term: 'URL',
                        description: 'https://'+this.shop+'/a/checkout/some/random/uri',
                    }
                ];
            }
        },
        methods: {
            goToUrl() {
                window.open('https://'+this.shop+'/a/checkout/some/random/uri', '_blank')
            }
        }
    }
</script>

<style>
    @media screen {
        .subtitle {
            margin: 0.5em 0 1em;
        }

        .title-subtitle {
            color: #637381;
        }
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
            margin: 0 5%;
            padding-top: 5%;
        }
    }

    @media screen and (max-width: 999px) {

    }

    @media screen and (min-width: 1000px) {

    }
</style>
