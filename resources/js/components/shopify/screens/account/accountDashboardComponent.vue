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

            <div class="banner-section" v-show="showPolarisBanner">
                <div class="inner-banner-section">
                    <polaris-vue-fade-up-transition>
                        <polaris-banner
                            v-if="showPolarisBanner"
                            :title="bannerTitle"
                            :status="bannerStatus"
                            @dismiss="() => {this.showPolarisBanner = false}">
                            <p>{{ bannerText }}</p>
                        </polaris-banner>
                    </polaris-vue-fade-up-transition>
                </div>
            </div>

            <polaris-layout>
                <shopify-publish-inventory-widget
                    :items="inventory"
                    :hmac="hmac"
                    :shop="shop"
                    @trigger-bulk-import="ajaxTriggerBulkImport"
                    @send-to-published-products="sendToPublishedProducs"
                ></shopify-publish-inventory-widget>

                <shopify-first-funnel-widget
                    v-if="inventory.length > 0"
                    :shop="shop"
                    :items="inventory"
                ></shopify-first-funnel-widget>
            </polaris-layout>
        </div>
    </div>
</template>

<script>
    export default {
        name: "accountDashboardComponent",
        props: ['shop', 'hmac', 'inventory'],
        data() {
            return {
                showPolarisBanner: false,
                bannerTitle: '',
                bannerStatus: '',
                bannerText: '',
                bannerIcon: ''
            }
        },
        computed: {
            infoIcon() {
                return 'M10 0C4.486 0 0 4.486 0 10s4.486 10 10 10 10-4.486 10-10S15.514 0 10 0m0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8m1-5v-3a1 1 0 0 0-1-1H9a1 1 0 1 0 0 2v3a1 1 0 0 0 1 1h1a1 1 0 1 0 0-2m-1-5.9a1.1 1.1 0 1 0 0-2.2 1.1 1.1 0 0 0 0 2.2'
            },
            flagIcon() {
                return 'M16.105 11.447L17.381 14H9v-2h4a1 1 0 0 0 1-1V8h3.38l-1.274 2.552a.993.993 0 0 0 0 .895zM2.69 4H12v6H4.027L2.692 4zm15.43 7l1.774-3.553A1 1 0 0 0 19 6h-5V3c0-.554-.447-1-1-1H2.248L1.976.782a1 1 0 1 0-1.953.434l4 18a1.006 1.006 0 0 0 1.193.76 1 1 0 0 0 .76-1.194L4.47 12H7v3a1 1 0 0 0 1 1h11c.346 0 .67-.18.85-.476a.993.993 0 0 0 .044-.972l-1.775-3.553z'
            },
            defaultIcon() {
                return '&lt;svg class=&quot;Polaris-Icon__Svg&quot; viewBox=&quot;0 0 20 20&quot;&gt;&lt;path d=&quot;M13.707 10.707a.997.997 0 0 1-1.414 0L11 9.414V17a1 1 0 1 1-2 0V9.414l-1.293 1.293a.999.999 0 1 1-1.414-1.414l3-3a.999.999 0 0 1 1.414 0l3 3a.999.999 0 0 1 0 1.414zM17 2a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1h-3a1 1 0 1 1 0-2h2V4H4v9h2a1 1 0 1 1 0 2H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h14z&quot; fill-rule=&quot;evenodd&quot;&gt;&lt;/path&gt;&lt;/svg&gt;'
            }
        },
        methods: {
            goToUrl() {
                window.open('https://'+this.shop+'/a/checkout/some/random/uri', '_blank')
            },
            ajaxTriggerBulkImport() {
                this.bannerTitle = 'Feature Not Available in Demo Build';
                this.bannerText = 'You bet your britches it will be available in the final version!';
                this.bannerStatus = 'info';
                this.bannerIcon = this.infoIcon;
                this.showPolarisBanner = true;
            },
            sendToPublishedProducs() {
                this.bannerTitle = 'Feature Available Soon';
                this.bannerText = 'We\'re hurrying dammit! Hold you\'re freakin\' horses. Jeez...';
                this.bannerStatus = 'warning';
                this.bannerIcon = this.flagIcon;
                this.showPolarisBanner = true;
            }
        },
        mounted() {

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

        .banner-section {
            margin-bottom: 2rem;
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
