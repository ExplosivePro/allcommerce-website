<template>
    <checkout-experience
        :has-timer="timerSegment"
        :has-express-checkout="expressCheckout"
        :line-items="items"
    ></checkout-experience>
</template>

<script>
    import CheckoutExperience from "../../screens/checkout/DefaultExperience";
    export default {
        name: "DefaultCheckoutExperienceContainer",
        components: {
            CheckoutExperience
        },
        props: ['items'],
        watch: {},
        data() {
            return {
                timerSegment: false,
                expressCheckout: false
            };
        },
        computed: {},
        methods: {},
        mounted() {
            console.log('DefaultCheckoutExperienceContainer mounted!', this.items);
        }
    }
</script>

<style scoped>

</style>
