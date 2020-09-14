<template>
<div class="row payment-section">
    <div class="inner-payment-section">
        <div class="order-form-column">
            <div class="inner-order-form">
                <div class="form-segment timer-segment" v-if="hasTimer"><p> Timer Segment</p></div>
                <div class="form-segment express-checkout-segment" v-if="hasExpressCheckout"> <p>Express Checkout Segment</p> </div>

                <div class="form-segment contact-info-segment">
                    <div class="inner-contact-info">
                        <polaris-form-layout>
                            <polaris-text-field
                                label="Contact Information"
                                type="email"
                                v-model="email"
                                placeholder="Email">
                            </polaris-text-field>
                            <polaris-checkbox label="Keep me up to date on news and exclusive offers" v-model="joinMailingList">
                            </polaris-checkbox>
                        </polaris-form-layout>
                    </div>
                </div>

                <div class="form-segment shipping-address-segment">
                    <div class="inner-shipping-address inner-form-segment">
                        <div class="segment-title">
                            <div class="inner-segment-title">
                                <p class="Polaris-Label__Text">Shipping Address</p>
                                <small><i>Please ensure shipping address is correct for faster delivery.</i></small>
                            </div>

                            <div class="shipping-method-content">
                                <div class="inner-shipping-method-content">
                                    <polaris-form-layout>
                                        <polaris-form-layout-group>
                                            <polaris-text-field placeholder="First name"></polaris-text-field>
                                            <polaris-text-field placeholder="Last name"></polaris-text-field>
                                            <polaris-text-field placeholder="Company (optional)"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group>
                                            <polaris-text-field placeholder="Address"></polaris-text-field>
                                            <polaris-text-field placeholder="Apt, suite, etc (optional)"></polaris-text-field>
                                            <polaris-text-field placeholder="City"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group condensed>
                                            <polaris-text-field placeholder="Country"></polaris-text-field>
                                            <polaris-text-field placeholder="State"></polaris-text-field>
                                            <polaris-text-field placeholder="Postal Code"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group>
                                            <polaris-text-field placeholder="Phone #"></polaris-text-field>
                                        </polaris-form-layout-group>
                                    </polaris-form-layout>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-segment billing-address-segment">
                    <div class="inner-billing-address inner-form-segment">
                        <div class="segment-title">
                            <div class="inner-segment-title">
                                <p class="Polaris-Label__Text">Billing Address</p>
                            </div>
                        </div>

                        <div class="billing-address-panel">
                            <div class="inner-billing-address-panel">

                                <div class="same-as-shipping-box">
                                    <input type="radio" name="sameAsShipping" :value="true" v-model="billingShippingSame">
                                    <label>Same as Shipping Address</label>
                                    <br>
                                    <input type="radio" name="sameAsShipping" :value="false" v-model="billingShippingSame">
                                    <label>Use a Different Billing Address</label>
                                </div>

                                <div class="billing-address-content" v-if="billingShippingSame === false">
                                    <div class="inner-billing-address-content">
                                        <polaris-form-layout>
                                            <polaris-form-layout-group>
                                                <polaris-text-field placeholder="First name"></polaris-text-field>
                                                <polaris-text-field placeholder="Last name"></polaris-text-field>
                                                <polaris-text-field placeholder="Company (optional)"></polaris-text-field>
                                            </polaris-form-layout-group>

                                            <polaris-form-layout-group>
                                                <polaris-text-field placeholder="Address"></polaris-text-field>
                                                <polaris-text-field placeholder="Apt, suite, etc (optional)"></polaris-text-field>
                                                <polaris-text-field placeholder="City"></polaris-text-field>
                                            </polaris-form-layout-group>

                                            <polaris-form-layout-group condensed>
                                                <polaris-text-field placeholder="Country"></polaris-text-field>
                                                <polaris-text-field placeholder="State"></polaris-text-field>
                                                <polaris-text-field placeholder="Postal Code"></polaris-text-field>
                                            </polaris-form-layout-group>

                                            <polaris-form-layout-group>
                                                <polaris-text-field placeholder="Phone #"></polaris-text-field>
                                            </polaris-form-layout-group>
                                        </polaris-form-layout>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-segment shipping-method-segment">
                    <div class="inner-shipping-method">
                        <div class="segment-title">
                            <div class="inner-segment-title">
                                <p class="Polaris-Label__Text">Shipping Method</p>
                            </div>
                        </div>

                        <div class="shipping-method-panel">
                            <div class="inner-shipping-method-panel inner-form-segment">
                                <div class="untoggled-shipping" v-if="!showShippingMethods">
                                    <div class="inner-untoggled-shipping">
                                        <div class="Polaris-Connected">
                                            <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                                <div class="Polaris-TextField" style="height: 5rem;">
                                                    <label id="PolarisTextField7" class="Polaris-TextField__Input" style="color: #000">
                                                        <i class="fad fa-truck fa-flip-horizontal"></i>
                                                        <span style="padding-left: 1.5em;">
                                                            <span>Enter your shipping address to see shipping options</span>
                                                        </span>
                                                    </label>
                                                    <div class="Polaris-TextField__Backdrop"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="toggled-shipping" v-else>
                                    <h1> Sup Shipping Methods? </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-segment payment-info-segment">
                    <div class="inner-payment-info-segment">
                        <div class="segment-title">
                            <div class="inner-segment-title">
                                <p class="Polaris-Label__Text">Payment Information</p>
                                <small><i>All transactions are secure and encrypted.</i></small>
                            </div>
                        </div>

                        <div class="payment-info-panel">
                            <div class="inner-payment-info-panel inner-form-segment">
                                <div class="Polaris-Connected">
                                    <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                                        <div class="Polaris-TextField" style="height: 5rem;">
                                            <label class="Polaris-TextField__Input" style="color: #000; z-index:100">
                                                <input type="radio" value="credit" name="paymentMethod" v-model="selectedPaymentMethod">
                                                <span style="padding-left: 1.5em;">
                                                    <span>Credit Card</span>
                                                </span>
                                            </label>
                                            <div class="Polaris-TextField__Backdrop"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="payment-pad">
                                    <polaris-form-layout>
                                        <polaris-form-layout-group>
                                            <polaris-text-field placeholder="Card Number"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group condensed>
                                            <polaris-text-field placeholder="Name on Card"></polaris-text-field>
                                            <polaris-text-field placeholder="MM/YY"></polaris-text-field>
                                            <polaris-text-field placeholder="CVV"></polaris-text-field>
                                        </polaris-form-layout-group>
                                    </polaris-form-layout>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-segment submit-info-segment">
                    <div class="inner-submit-info inner-form-segment">
                        <div class="return-side">
                            <div class="inner-return-side">
                                <a href="/cart">Return to Cart</a>
                            </div>
                        </div>
                        <div class="submit-side">
                            <div class="inner-submit">
                                <polaris-button primary>
                                    <i class="fad fa-arrow-alt-right"></i><span style="padding-left: 1em;"><b>Submit Order</b></span>
                                </polaris-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="order-summary-column">
            <div class="inner-order-summary">
                <div class="form-segment main-order-summary-segment">
                    <div class="inner-main-order-summary">
                        <div class="segment-title">
                            <div class="inner-segment-title">
                                <p class="Polaris-Label__Text">Order Summary</p>
                            </div>
                        </div>

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
                                                                            <!---->
                                                                            <div class="line-price">${{ item.variant.price * item.qty  }}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!---->
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
                                                            <input type="text" class="Polaris-TextField__Input" v-model="giftCardCode" placeholder="Gift Card or Discount Code" :style="(giftCardCode !== '') ? 'top:5px;' : ''">
                                                            <div class="Polaris-TextField__Backdrop"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </float-label>

                                            <polaris-button slot="action" :primary="giftCardCode !== ''" :disabled="giftCardCode === ''" @click="enterGiftCode()">
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
                                                <p>${{ subTotal }}</p>
                                            </div>
                                        </div>
                                        <div class="subtotal-row">
                                            <div class="inner-subtotal-row">
                                                <p>Shipping</p>
                                                <p><small>Calculated below</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="total-price-piece bar-separator">
                                    <div class="inner-total-price-piece">
                                        <div class="total-row">
                                            <div class="inner-total-row">
                                                <p>Total</p>
                                                <p>${{ subTotal }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        name: "DefaultExperience",
        components: {
            FloatLabel
        },
        props: ['hasTimer', 'hasExpressCheckout', 'lineItems'],
        watch: {
            billingShippingSame(flag) {
                console.log('billingShippingSame - Setting sameAsShipping to - '+ flag)
                this.sameAsShipping = flag;
            }
        },
        data() {
            return {
                joinMailingList: true,
                billingShippingSame: true,
                email: '',
                giftCardCode: '',
                showShippingMethods: false,
                selectedPaymentMethod: 'credit',
                subTotal: '1.00',
                total: '1.00'
            };
        },
        methods: {
            enterGiftCode() {
                alert('Heyo! - '+this.giftCardCode);
            },
            calculateSubTotal() {
                let amt = 0;
                for(let idx in this.lineItems) {
                    amt = amt + (this.lineItems[idx].variant.price * this.lineItems[idx].qty)
                }

                this.subTotal = parseFloat(amt).toFixed(2);

                return this.subTotal;
            },
            calculateTotal() {
                console.log('Calculating total')
                let amt = 0;
                for(let idx in this.lineItems) {
                    amt = amt + (this.lineItems[idx].variant.price * this.lineItems[idx].qty)
                }

                this.total = parseFloat(amt).toFixed(2);
                return this.total;
            },
        },
        mounted() {
            this.calculateSubTotal();
            this.calculateTotal();
        }
    }
</script>

<style>
    .Polaris-Label__Text {
        font-weight: 700;
        font-size: 1.25em;
        letter-spacing: 0.1em;
    }

    .inner-submit .Polaris-Button--primary, .inner-gift-card-field .Polaris-Button--primary {
        background: linear-gradient(to bottom, #000, #000b14);
        border-color: #000b14;
        box-shadow: inset 0 1px 0 0 #000b14, 0 1px 0 0 rgba(22, 29, 37, 0.05), 0 0 0 0 transparent;
    }

    .Polaris-Connected__Item--primary:not(:last-child) * {
        border-top-right-radius: 0.25rem !important;
        border-bottom-right-radius: 0.25rem !important;
    }

    .Polaris-Connected__Item--primary:not(:first-child) * {
        border-top-left-radius: 0.25rem !important;
        border-bottom-left-radius: 0.25rem !important;
    }

    .inner-gift-card-field .Polaris-TextField__Input {
        min-height: 4.5rem;
    }

    .inner-gift-card-field .Polaris-Button{
        min-height: 4.5rem;
    }

    .inner-gift-card-field .vfl-label-on-input {
        top: 0em;
        z-index: 1000;
        left: 1em;
    }

    .inner-payment-info-panel .payment-pad .Polaris-TextField__Input {
        height: 4.5rem;
    }
</style>

<style scoped>
    @media screen {
        .payment-section {
            height: auto;
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
            height: auto;
            min-height: 75%;
        }

        .inner-payment-section {
            flex-flow: row;
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
