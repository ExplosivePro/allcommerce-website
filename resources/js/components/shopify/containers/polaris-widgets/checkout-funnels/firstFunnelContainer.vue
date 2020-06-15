<template>
    <first-funnel-widget
        :title="title"
        :items="items"
        @try-it-pressed="goToFunnel"
    ></first-funnel-widget>
</template>

<script>
    export default {
        name: "firstFunnelContainer",
        props: ['shop','items', 'funnel'],
        data() {
            return {
                title: '',
                name: '',
                url: '',
                items: []
            };
        },
        methods: {
            goToFunnel() {
                window.open(this.url, '_blank');
            },
            generateItems() {
                return [
                    {
                        term: 'Product',
                        description: this.name
                    },
                    {
                        term: 'URL',
                        description: this.url,
                    }
                ];
            },
        },
        mounted() {
            this.url = this.funnel.url;
            this.name = this.funnel.name;
            this.items = this.generateItems()
        }
    }
</script>

<style scoped>

</style>
