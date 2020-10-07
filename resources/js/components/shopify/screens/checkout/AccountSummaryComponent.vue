<template>
    <div class="summary-segment-content">
        <div class="inner-summary-segment-content">
            <div class="order-breakdown-piece">
                <div class="inner-order-breakdown-piece">
                    <div class="line-items">
                        <div class="inner-line-items">
                            <div class="line-item" v-for="(item, idx) in lineItems">
                                <div class="inner-line-item">
                                    <ul class="cart-line-items-v2">
                                        <li>
                                            <div class="row">
                                                <div class="col-12 d-flex align-items-center cart-line-item-container">
                                                    <div class="image-container">
                                                        <div class="item-image-holder" :style="'background-image: url('+item.image.src+');'">
                                                            <div class="item-quanitity-indicator">
                                                                {{ item.qty }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="title-container d-flex flex-fill flex-column justify-content-center px-4">
                                                        <div class="line-title d-flex flex-row align-items-center mb-1">
                                                            <span><b>{{ item.item.title }}</b> by {{ item.item.vendor }}</span>
                                                        </div>
                                                        <div class="line-variant-title">
                                                            <span><i>{{ item.variant.title }}</i></span>
                                                        </div>
                                                    </div>
                                                    <div class="price-container d-flex flex-fill flex-column justify-content-center text-right">
                                                        <div class="line-price">${{ item.variant.price * item.qty  }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gift-card-field-piece bar-separator">
                <div class="inner-gift-card-field inner-form-segment2">
                    <polaris-setting-action>
                        <float-label slot="children">
                            <div class="Polaris-Connected" >
                                <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                    <div class="Polaris-TextField">
                                        <input type="text"
                                               class="Polaris-TextField__Input"
                                               v-model="giftCardCode"
                                               placeholder="Gift Card or Discount Code"
                                               :style="(giftCardCode !== '') ? 'top:5px;' : ''"
                                               :disabled="disableFields"
                                        />
                                        <div class="Polaris-TextField__Backdrop"></div>
                                    </div>
                                </div>
                            </div>
                        </float-label>

                        <polaris-button slot="action" v-model="giftCardCode" :primary="giftCardCode !== ''" :disabled="giftCardCode === ''" @click="enterGiftCode()">
                            Apply
                        </polaris-button>
                    </polaris-setting-action>
                </div>
            </div>
            <div class="subtotal-piece bar-separator">
                <div class="inner-subtotal-piece">
                    <div class="subtotal-row">
                        <div class="inner-subtotal-row">
                            <p>Subtotal</p>
                            <p>${{ pricing.subTotal }}</p>
                        </div>
                    </div>
                    <div class="subtotal-row">
                        <div class="inner-subtotal-row">
                            <p>Shipping</p>
                            <p v-if="shippingText !== ''" v-html="shippingText"></p>
                        </div>
                        <div  v-if="shippingLine.length > 0">
                            <p>stuff goes here!</p>
                        </div>
                    </div>
                    <div class="subtotal-row">
                        <div class="inner-subtotal-row">
                            <p>Tax</p>
                            <p v-if="taxText !== ''" v-html="taxText"></p>
                        </div>
                        <div  v-if="taxLines.length > 0">
                            <div class="inner-subtotal-row" v-for="(line, idx) in taxLines">
                                <small style="padding-left: 1em">{{ line.title }} {{ (line.rate * 100) }}%</small>
                                <p style="padding-left: 1em">${{ line.price }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="total-price-piece bar-separator">
                <div class="inner-total-price-piece">
                    <div class="total-row">
                        <div class="inner-total-row">
                            <p>Total</p>
                            <p>${{ pricing.total }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import FloatLabel from "vue-float-label/components/FloatLabel";

export default {
    name: "AccountSummaryComponent",
    components: {
        FloatLabel,
    },
    props: ['lineItems', 'pricing', 'taxText', 'shippingText'],
    watch: {},
    data() {
        return {
            giftCardCode: '',
            disableFields: false,
            subTotal: 0.00,
            taxLines: [],
            shippingLine: [],
            typeOfTax: '',
        };
    },
    computed: {

    },
    methods: {
        enterGiftCode() {
            this.$emit('gift-submit', this.giftCardCode)
        },
    },
    mounted() {
        console.log('AccountSummaryComponentPresenter mounted!')
    }
}
</script>

<style scoped>
    @media screen {
        .payment-section {
            /*height: auto;*/
        }

        .inner-payment-section {
            height: 100%;
            display: flex;
        }

        .inner-order-form {
            display: flex;
            flex-flow: column;
        }

        .contact-info-segment {
            width: 100%;
        }

        .inner-submit-info {
            display: flex;
            flex-flow: row;
            justify-content: space-between;
        }

        .inner-order-summary {
            display: flex;
            flex-flow: column;
        }

        .bar-separator {
            border-top: 1px solid #dfe3e8;
        }

        .inner-subtotal-piece {
            display: flex;
            flex-flow: column;
        }

        .inner-subtotal-row {
            display: flex;
            flex-flow: row;
            justify-content: space-between;
            margin: 2% 0 1%;
        }

        .inner-total-price-piece {
            display: flex;
            flex-flow: column;
        }

        .inner-total-row {
            display: flex;
            flex-flow: row;
            justify-content: space-between;
            margin: 4% 0 1%;
        }

        .same-as-shipping-box {
            margin-top: 2.5%;
        }

        .same-as-shipping-box input {
            margin: 0;
        }

        .line-items {
            width: 100%;
        }

        .inner-line-items {
            display: flex;
            flex-flow: column;
        }

        .line-item {
            width: 100%;
        }

        .inner-line-item {
            display: flex;
            flex-wrap: wrap;
        }

        .cart-line-items-v2 {
            list-style: none;
            padding: 0;
            margin: 0 0 21px 0;
            width: 100%;
        }

        .cart-line-items-v2 li {
            padding: 0;
            margin: 0;
        }

        .cart-line-items-v2 li .row {
            display: flex;
            flex-wrap: wrap;

            margin-top: 1em;
        }

        .cart-line-item-container {
            display: flex;
            width: 100%;
        }

        .cart-line-items-v2 .item-image-holder {
            position: relative;
            width: 64px;
            height: 64px;
            -webkit-box-shadow: inset 0 0 0 1px #d9d9d9;
            box-shadow: inset 0 0 0 1px #d9d9d9;
            background-repeat: no-repeat;
            -webkit-background-size: auto 100%;
            -moz-background-size: auto 100%;
            background-size: auto 100%;
            background-position: center center;
            border-radius: 8px;
        }

        .cart-line-items-v2 .item-quanitity-indicator {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 20px;
            height: 20px;
            background-color: #353535;
            border-radius: 25%;
            color: #fff;
            text-align: center;
            line-height: 20px;
            font-size: 0.857em;
            -webkit-box-shadow: 0 0 0 2px rgba(0,0,0,0.2);
            box-shadow: 0 0 0 2px rgba(0,0,0,0.2);
        }

        .cart-line-items-v2 .title-container {
            overflow: hidden;
            color: #717171;
            padding: 0 16px !important;
            justify-content: center!important;
            flex: 1 1 auto!important;
            flex-direction: column!important;
            display: flex!important;
        }
    }

    @media screen and (max-width: 999px) {
        .inner-payment-section {
            flex-flow: column;
            margin-top: 2.5%;
        }

        .inner-payment-section.loading {
            height: 100%;
            justify-content: center;
            align-items: center;
        }

        .inner-payment-section .loading-piece {
            height: 100%;
            margin: 20em 0;
        }

        .order-form-column {
            width: 100%;
            order: 2;
        }

        .inner-order-form {
            padding: 0 2.5%;

        }

        .inner-contact-info, .inner-main-order-summary {
            padding: 5% 0;
        }

        .inner-form-segment {
            padding: 1% 0 5%;
        }

        .inner-form-segment2 {
            padding: 5% 0;
        }

        .order-summary-column {
            width: 100%;
            order: 1;

            margin-bottom: 5%;
        }

        .inner-order-summary {
            padding: 0 2.5%;
        }
    }

    @media screen and (min-width: 1000px) {
        .payment-section {
            /*height: auto;
            min-height: 75%;*/
        }

        .inner-payment-section {
            flex-flow: row;
        }

        .inner-payment-section.loading {
            height: 100%;
            align-content: center;
            justify-content: center;
        }

        .inner-payment-section .loading-piece {
            height: 100%;
            margin: 25% 0;
        }

        .order-form-column {
            height: 100%;
            width: 50%;
            order: 1;
        }

        .inner-order-form {
            padding: 0 3%;
        }

        .inner-contact-info, .inner-main-order-summary {
            padding: 5% 0;
        }

        .inner-form-segment {
            padding: 1% 0 5%;
        }

        .inner-form-segment2 {
            padding: 5% 0;
        }

        .order-summary-column {
            height: 100%;
            width: 50%;
            order: 2;
        }

        .inner-order-summary {
            padding: 0 2.5% 0 5%;
        }
    }
</style>
