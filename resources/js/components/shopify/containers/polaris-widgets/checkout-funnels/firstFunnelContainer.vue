<template>
    <first-funnel-widget
        :items="generateItems()"
    ></first-funnel-widget>
</template>

<script>
    export default {
        name: "firstFunnelContainer",
        props: ['shop','items'],
        methods: {
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
            },
        }
    }
</script>

<style scoped>

</style>
