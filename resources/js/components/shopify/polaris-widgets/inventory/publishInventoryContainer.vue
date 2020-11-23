<template>
        <publish-inventory-widget
            :shop="shop"
            :title="title"
            :desc="description"
            :items="curatedInventory"
            :scenario="scenario"
            :ready="ready"
            :card-title="cardTitle"
            :card-desc="cardDesc"
            @trigger-bulk-import="ajaxTriggerBulkImport"
            @send-to-published-products="sendToPublishedProducs"
        ></publish-inventory-widget>
</template>

<script>
    import publishInventoryWidget from "../../../../screens/shopify/polaris-widgets/inventory/publishInventoryWidget";
    export default {
        name: "publishInventoryContainer",
        components: { publishInventoryWidget },
        props: ['items', 'hmac', 'shop'],
        data() {
            return {
                ready: false,
                title: '',
                description: '',
                cardTitle: '',
                cardDesc: '',
                scenario: '',
                curatedInventory: [],
            };
        },
        computed: {

        },
        methods: {
            processItems() {
                console.log('Processing Inventory', this.inventory);
                if(this.items.length > 0) {
                    this.title = 'Publish Your Inventory!';
                    this.description = 'Publishing items is the key to success in the AllCommerce ecosystem.';
                }
                else {
                    console.log('Actually I\'m not lol, there\'s no inventory! Derp.');
                    this.title = 'Step One: Create some or Open Up Your Inventory!';
                    this.description = 'Publish your items on AllCommerce in order to make Checkout Funnels!';
                    this.cardTitle = ''
                }

                this.ajaxGetNewInventoryAvailable();
            },
            ajaxGetNewInventoryAvailable() {
                let _this = this;

                let payload = this.hmac;

                axios.post('/api/shopify/inventory', payload)
                    .then(function (response) {
                        console.log('Inventory response -', response);
                        let data = response.data;

                        if(data['success'] === true) {
                            console.log('Success!');

                            if(data['new_products']['listings'].length > 0) {

                                let count = data['new_products']['listings'].length;
                                let verb = count === 1 ? 'is': 'are';
                                let product_noun = count === 1 ? 'product' : 'products';
                                let adverb = count === 1 ? 'it' : 'them';
                                _this.description = _this.description + ' Publishing items is the key to success in the AllCommerce ecosystem. All you need is at least one product published to activate Checkout Funnels, Shop Templates, and many other features! ';
                                _this.cardTitle = `There ${verb} ${count} new ${product_noun} to publish!`;
                                _this.cardDesc  = `Publish ${adverb} into AllCommerce to start using them in your checkouts!`;
                                _this.scenario = 'publish';
                                _this.ready = true;
                            }
                            else {
                                if(_this.items.length > 0) {
                                    _this.cardTitle = 'Looks like you\'re all set!' ;
                                    _this.cardDesc  = 'When you create new products, be sure to make them available for AllCommerce!';
                                }
                                else
                                {
                                    console.log('Rendering Go to Products Page!');
                                    _this.cardTitle = 'No Products Available';
                                    _this.cardDesc  = 'There are no products available for us to publish! Go create some products or make those you have visible to us!';
                                    _this.scenario = 'empty';
                                }

                                _this.ready = true;
                            }
                        }
                        else {
                            console.log('Rendering Fail Scenario!');
                            _this.cardTitle = data['reason'];
                            _this.cardDesc  = data['msg'];
                            _this.ready = true;
                        }
                    })
            },
            ajaxTriggerBulkImport() {
                this.$emit('trigger-bulk-import')

            },
            sendToPublishedProducs() {
                this.$emit('send-to-published-products')
            }
        },
        mounted() {
            console.log('Shopify Publish Inventory Widget Container Mounted!');
            this.processItems();
        }
    }
</script>

<style scoped>

</style>
