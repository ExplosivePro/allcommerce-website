<template>
    <polaris-layout-annotated-section
        :title="title"
        :description="desc">
        <polaris-card
            :title="cardTitle"
            sectioned>
            <polaris-skeleton-body-text v-if="!ready"/>
            <div class="inventory-segment" v-else>
                <div class="inner-inventory-segment">
                    <p>{{ cardDesc }}</p>

                    <div class="separated-row">
                        <polaris-setting-action
                            v-if="scenario === 'empty'"
                        >
                            <polaris-button slot="action" primary @click="redirect('https://'+shop+'/admin/products')">
                                Take Me There!
                            </polaris-button>
                            <p slot="children"><polaris-text-style variation="strong">Ready to manage your products?</polaris-text-style></p>
                            ></polaris-setting-action>
                        <polaris-setting-action
                            v-if="scenario === 'publish'"
                        >
                            <polaris-button slot="action" primary @click="actionEmitter('trigger-bulk-import')">
                                Bulk Import!
                            </polaris-button>
                            <p slot="children"><polaris-text-style variation="strong">Publish them all at once!</polaris-text-style></p>
                            ></polaris-setting-action>
                    </div>

                    <div class="separated-row">
                        <polaris-setting-action
                            v-if="scenario === 'publish'"
                        >
                            <polaris-button slot="action" primary @click="actionEmitter('send-to-published-products')">
                                Published Products
                            </polaris-button>
                            <p slot="children"><polaris-text-style variation="strong">Feeling choosy? We gotchu!</polaris-text-style></p>
                        </polaris-setting-action>
                    </div>
                </div>
            </div>
        </polaris-card>
    </polaris-layout-annotated-section>
</template>

<script>
    export default {
        name: "publishInventoryWidget",
        props: ['shop', 'title', 'desc', 'items', 'scenario','ready','cardTitle', 'cardDesc'],
        methods: {
            redirect(to) {
                let Redirect = actions.Redirect;
                Redirect.create(shopifyApp).dispatch(Redirect.Action.REMOTE, to);
            },
            actionEmitter(action) {
                this.$emit(action)
            }
        }

    }
</script>

<style scoped>
    @media screen {
        .separated-row {
            margin-top: 2em;
        }
    }
</style>
