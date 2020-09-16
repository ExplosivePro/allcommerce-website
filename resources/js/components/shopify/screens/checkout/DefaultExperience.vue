<template>
<div class="row payment-section">
    <div class="inner-payment-section loading" v-if="loading">
        <div class="loading-piece" v-if="loading">
            <div class="inner-loading-piece">
                <sexy-hurricane-loading
                    loading-msg="Loading Your Secure Checkout!"
                    override-icon="fad fa-spinner-third"
                    override-icon-size="7.5em"
                ></sexy-hurricane-loading>
            </div>
        </div>
    </div>

    <div class="inner-payment-section" v-else>
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
                                :disabled="disableFields"
                                placeholder="Email">
                            </polaris-text-field>
                            <polaris-checkbox label="Keep me up to date on news and exclusive offers" v-model="joinMailingList" :disabled="disableFields">
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
                                            <polaris-text-field v-model="shippingFirst" placeholder="First name" :disabled="disableFields"></polaris-text-field>
                                            <polaris-text-field v-model="shippingLast" placeholder="Last name" :disabled="disableFields"></polaris-text-field>
                                            <polaris-text-field v-model="shippingCompany" placeholder="Company (optional)" :disabled="disableFields"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group>
                                            <polaris-text-field v-model="shippingAddress" placeholder="Address" :disabled="disableFields"></polaris-text-field>
                                            <polaris-text-field v-model="shippingApt" placeholder="Apt, suite, etc (optional)" :disabled="disableFields"></polaris-text-field>
                                            <polaris-text-field v-model="shippingCity" placeholder="City" :disabled="disableFields"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group condensed>
                                            <polaris-select
                                                v-model="shippingCountry"
                                                :disabled="disableFields"
                                                :options="countries"
                                                placeholder="Select a Country">
                                            </polaris-select>
                                            <polaris-select
                                                v-model="shippingState"
                                                :disabled="disableFields"
                                                :options="stateDrops[shippingCountry]"
                                                :placeholder="(shippingCountry === 'us') ? 'Select a State' : 'Select a Province'">
                                            </polaris-select>
                                            <polaris-text-field v-model="shippingZip" placeholder="Postal Code" :disabled="disableFields"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group>
                                            <polaris-text-field v-model="shippingPhone" placeholder="Phone #" :disabled="disableFields"></polaris-text-field>
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
                                    <input type="radio" name="sameAsShipping" :value="true" v-model="billingShippingSame" :disabled="disableFields">
                                    <label>Same as Shipping Address</label>
                                    <br>
                                    <input type="radio" name="sameAsShipping" :value="false" v-model="billingShippingSame" :disabled="disableFields">
                                    <label>Use a Different Billing Address</label>
                                </div>

                                <div class="billing-address-content" v-if="billingShippingSame === false">
                                    <div class="inner-billing-address-content">
                                        <polaris-form-layout>
                                            <polaris-form-layout-group>
                                                <polaris-text-field v-model="billingFirst" placeholder="First name" :disabled="disableFields"></polaris-text-field>
                                                <polaris-text-field v-model="billingLast" placeholder="Last name" :disabled="disableFields"></polaris-text-field>
                                                <polaris-text-field v-model="billingCompany" placeholder="Company (optional)" :disabled="disableFields"></polaris-text-field>
                                            </polaris-form-layout-group>

                                            <polaris-form-layout-group>
                                                <polaris-text-field v-model="billingAddress" placeholder="Address" :disabled="disableFields"></polaris-text-field>
                                                <polaris-text-field v-model="billingApt"placeholder="Apt, suite, etc (optional)" :disabled="disableFields"></polaris-text-field>
                                                <polaris-text-field v-model="billingCity" placeholder="City" :disabled="disableFields"></polaris-text-field>
                                            </polaris-form-layout-group>

                                            <polaris-form-layout-group condensed>
                                                <polaris-select
                                                    v-model="billingCountry"
                                                    :disabled="disableFields"
                                                    :options="countries"
                                                    placeholder="Select a Country">
                                                </polaris-select>
                                                <polaris-select
                                                    v-model="billingState"
                                                    :disabled="disableFields"
                                                    :options="stateDrops[billingCountry]"
                                                    :placeholder="(billingCountry === 'us') ? 'Select a State' : 'Select a Province'">
                                                </polaris-select>
                                                <polaris-text-field v-model="billingZip" placeholder="Postal Code" :disabled="disableFields"></polaris-text-field>
                                            </polaris-form-layout-group>

                                            <polaris-form-layout-group>
                                                <polaris-text-field v-model="billingPhone" placeholder="Phone #" :disabled="disableFields"></polaris-text-field>
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
                                            <polaris-text-field placeholder="Card Number" :disabled="disableFields"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group condensed>
                                            <polaris-text-field placeholder="Name on Card" :disabled="disableFields"></polaris-text-field>
                                            <polaris-text-field placeholder="MM/YY" :disabled="disableFields"></polaris-text-field>
                                            <polaris-text-field placeholder="CVV" :disabled="disableFields"></polaris-text-field>
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
    import SexyHurricaneLoading from "../../../presenters/widgets/loading/SexyHurricane";

    export default {
        name: "DefaultExperience",
        components: {
            FloatLabel,
            SexyHurricaneLoading
        },
        props: [
            'hasTimer', 'hasExpressCheckout', 'lineItems', 'loading', 'disableFields',
            'countries', 'stateDrops'
        ],
        watch: {
            email(addy) {
                let payload = {
                    method: 'email',
                    value: addy
                };
                this.$emit('updated', payload);
            },
            joinMailingList(flag) {
                let payload = {
                    method: 'emailList',
                    value: flag
                };
                this.$emit('updated', payload);
            },
            billingShippingSame(flag) {
                console.log('billingShippingSame - Setting sameAsShipping to - '+ flag);

                if (flag) {
                    this.billingFirst = this.shippingFirst;
                    this.billingLast = this.shippingLast;
                    this.billingCompany = this.shippingCompany;
                    this.billingAddress = this.shippingAddress;
                    this.billingApt = this.shippingApt
                    this.billingCity = this.shippingCity;
                    this.billingCountry = this.shippingCountry;
                    this.billingState = this.shippingState;
                    this.billingZip = this.shippingZip;
                    this.billingPhone = this.shippingPhone;
                }
                else {
                    this.billingFirst = '';
                    this.billingLast = '';
                    this.billingCompany = '';
                    this.billingAddress = '';
                    this.billingApt = ''
                    this.billingCity = '';
                    this.billingCountry = 'us';
                    this.billingState = '';
                    this.billingZip = '';
                    this.billingPhone = '';
                }

                let payload = {
                    method: 'billingShippingSame',
                    value: flag
                };
                this.$emit('updated', payload)
            },
            shippingFirst(val) {
                if(this.billingShippingSame) {
                    this.billingFirst = val;
                }

                let payload = {
                    method: 'shippingFirst',
                    value: val
                };
                this.$emit('updated', payload);
            },
            shippingLast(val) {
                if(this.billingShippingSame) {
                    this.billingLast = val;
                }

                let payload = {
                    method: 'shippingLast',
                    value: val
                };
                this.$emit('updated', payload);
            },
            shippingCompany(val) {
                if(this.billingShippingSame) {
                    this.billingCompany = val;
                }

                let payload = {
                    method: 'shippingCompany',
                    value: val
                };
                this.$emit('updated', payload);
            },
            shippingAddress(val) {
                if(this.billingShippingSame) {
                    this.billingAddress = val;
                }

                let payload = {
                    method: 'shippingAddress',
                    value: val
                };
                this.$emit('updated', payload);
            },
            shippingApt(val) {
                if(this.billingShippingSame) {
                    this.billingApt = val;
                }

                let payload = {
                    method: 'shippingApt',
                    value: val
                };
                this.$emit('updated', payload);
            },
            shippingCity(val) {
                if(this.billingShippingSame) {
                    this.billingCity = val;
                }

                let payload = {
                    method: 'shippingCity',
                    value: val
                };
                this.$emit('updated', payload);
            },
            shippingCountry(val) {
                if(this.billingShippingSame) {
                    this.billingCountry = val;
                }

                let payload = {
                    method: 'shippingCountry',
                    value: val
                };
                this.$emit('updated', payload);
            },
            shippingState(val) {
                if(this.billingShippingSame) {
                    this.billingState = val;
                }

                let payload = {
                    method: 'shippingState',
                    value: val
                };
                this.$emit('updated', payload);
            },
            shippingZip(val) {
                if(this.billingShippingSame) {
                    this.billingZip = val;
                }

                let payload = {
                    method: 'shippingZip',
                    value: val
                };
                this.$emit('updated', payload);
            },
            shippingPhone(val) {
                if(this.billingShippingSame) {
                    this.billingPhone = val;
                }

                let payload = {
                    method: 'shippingPhone',
                    value: val
                };
                this.$emit('updated', payload);
            },

            billingFirst(val) {
                let payload2 = {
                    method: 'billingFirst',
                    value: val
                };
                this.$emit('updated', payload2);
            },
            billingLast(val) {
                let payload2 = {
                    method: 'billingLast',
                    value: val
                };
                this.$emit('updated', payload2);
            },
            billingCompany(val) {
                let payload2 = {
                    method: 'billingCompany',
                    value: val
                };
                this.$emit('updated', payload2);
            },
            billingAddress(val) {
                let payload2 = {
                    method: 'billingAddress',
                    value: val
                };
                this.$emit('updated', payload2);
            },
            billingApt(val) {
                let payload2 = {
                    method: 'billingApt',
                    value: val
                };
                this.$emit('updated', payload2);
            },
            billingCity(val) {
                let payload2 = {
                    method: 'billingCity',
                    value: val
                };
                this.$emit('updated', payload2);
            },
            billingCountry(val) {
                let payload2 = {
                    method: 'billingCountry',
                    value: val
                };
                this.$emit('updated', payload2);
            },
            billingState(val) {
                let payload2 = {
                    method: 'billingState',
                    value: val
                };
                this.$emit('updated', payload2);
            },
            billingZip(val) {
                let payload2 = {
                    method: 'billingZip',
                    value: val
                };
                this.$emit('updated', payload2);
            },
            billingPhone(val) {
                let payload2 = {
                    method: 'billingPhone',
                    value: val
                };
                this.$emit('updated', payload2);
            },
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
                total: '1.00',

                shippingFirst: '',
                shippingLast: '',
                shippingCompany: '',
                shippingAddress: '',
                shippingApt: '',
                shippingCity: '',
                shippingCountry: 'us',
                shippingState: '',
                shippingZip: '',
                shippingPhone: '',

                billingFirst: '',
                billingLast: '',
                billingCompany: '',
                billingAddress: '',
                billingApt: '',
                billingCity: '',
                billingCountry: 'us',
                billingState: '',
                billingZip: '',
                billingPhone: ''
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

            let payload = {
                method: 'shippingCountry',
                value: this.shippingCountry
            };
            this.$emit('updated', payload);

            payload.method = 'billingCountry'
            this.$emit('updated', payload);
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
            height: auto;
            min-height: 75%;
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
