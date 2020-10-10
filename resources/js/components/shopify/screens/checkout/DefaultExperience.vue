<template>
<div class="row payment-section" :style="paymentSectionHeight">
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
    <div class="inner-payment-section one-click" v-else-if="showOneClick">
        <div class="one-click-piece">
            <shopify-default-one-step
                :show-form="launchOneClick"
            ></shopify-default-one-step>
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
                                :error="(shippingEmailError === '') ? false : shippingEmailError"
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
                                            <polaris-text-field v-model="shippingFirst" placeholder="First name" :error="(shippingFirstError === '') ? false : shippingFirstError"></polaris-text-field>
                                            <polaris-text-field v-model="shippingLast" placeholder="Last name" :error="(shippingLastError === '') ? false : shippingLastError"></polaris-text-field>
                                            <polaris-text-field v-model="shippingCompany" placeholder="Company (optional)"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group>
                                            <polaris-text-field v-model="shippingAddress" placeholder="Address" :error="(shippingAddressError === '') ? false : shippingAddressError"></polaris-text-field>
                                            <polaris-text-field v-model="shippingApt" placeholder="Apt, suite, etc (optional)" ></polaris-text-field>
                                            <polaris-text-field v-model="shippingCity" placeholder="City" :error="(shippingCityError === '') ? false : shippingCityError"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group condensed>
                                            <polaris-select
                                                v-model="shippingCountry"
                                                context.state.billing
                                                :options="countries"
                                                placeholder="Select a Country">
                                            </polaris-select>
                                            <polaris-select
                                                v-model="shippingState"
                                                context.state.billing
                                                :options="stateDrops[shippingCountry]"
                                                :placeholder="(shippingCountry === 'us') ? 'Select a State' : 'Select a Province'">
                                            </polaris-select>
                                            <polaris-text-field v-model="shippingZip" placeholder="Postal Code" :error="(shippingZipError === '') ? false : shippingZipError"></polaris-text-field>
                                        </polaris-form-layout-group>

                                        <polaris-form-layout-group>
                                            <polaris-text-field v-model="shippingPhone" placeholder="Phone #" :error="(shippingPhoneError === '') ? false : shippingPhoneError"></polaris-text-field>
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
                                    <input type="radio" name="sameAsShipping" :value="true" v-model="billingShippingSame" :disabled="!isShippingValid">
                                    <label>Same as Shipping Address</label>
                                    <br>
                                    <input type="radio" name="sameAsShipping" :value="false" v-model="billingShippingSame" :disabled="!isShippingValid">
                                    <label>Use a Different Billing Address</label>
                                </div>

                                <slide-y-up-transition>
                                    <div class="billing-address-content" v-if="billingShippingSame === false">
                                        <div class="inner-billing-address-content">
                                            <polaris-form-layout>
                                                <polaris-form-layout-group>
                                                    <polaris-text-field v-model="billingFirst" placeholder="First name" :error="(billingFirstError === '') ? false : billingFirstError"></polaris-text-field>
                                                    <polaris-text-field v-model="billingLast" placeholder="Last name" :error="(billingLastError === '') ? false : billingLastError"></polaris-text-field>
                                                    <polaris-text-field v-model="billingCompany" placeholder="Company (optional)"></polaris-text-field>
                                                </polaris-form-layout-group>

                                                <polaris-form-layout-group>
                                                    <polaris-text-field v-model="billingAddress" placeholder="Address" :error="(billingAddressError === '') ? false : billingAddressError"></polaris-text-field>
                                                    <polaris-text-field v-model="billingApt"placeholder="Apt, suite, etc (optional)"></polaris-text-field>
                                                    <polaris-text-field v-model="billingCity" placeholder="City" :error="(billingCityError === '') ? false : billingCityError"></polaris-text-field>
                                                </polaris-form-layout-group>

                                                <polaris-form-layout-group condensed>
                                                    <polaris-select
                                                        v-model="billingCountry"
                                                        context.state.billing
                                                        :options="countries"
                                                        placeholder="Select a Country">
                                                    </polaris-select>
                                                    <polaris-select
                                                        v-model="billingState"
                                                        context.state.billing
                                                        :options="stateDrops[billingCountry]"
                                                        :placeholder="(billingCountry === 'us') ? 'Select a State' : 'Select a Province'">
                                                    </polaris-select>
                                                    <polaris-text-field v-model="billingZip" placeholder="Postal Code" :error="(billingZipError === '') ? false : billingZipError"></polaris-text-field>
                                                </polaris-form-layout-group>

                                                <polaris-form-layout-group>
                                                    <polaris-text-field v-model="billingPhone" placeholder="Phone #" :error="(billingPhoneError === '') ? false : billingPhoneError"></polaris-text-field>
                                                </polaris-form-layout-group>
                                            </polaris-form-layout>
                                        </div>
                                    </div>
                                </slide-y-up-transition>

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

                        <shipping-method-panel></shipping-method-panel>
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

                        <shopify-account-summary></shopify-account-summary>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import ShippingMethodPanel from "../../containers/checkout/ShopifyShippingMethodsContainer";
    import SexyHurricaneLoading from "../../../presenters/widgets/loading/SexyHurricane";

    export default {
        name: "DefaultExperience",
        components: {
            ShippingMethodPanel,
            SexyHurricaneLoading
        },
        props: [
            'hasTimer', 'hasExpressCheckout', 'lineItems', 'loading', 'disableFields',
            'countries', 'stateDrops', 'shippingLine', 'pricing', 'tax', 'showOneClick',
            'prefillData',
        ],
        watch: {
            prefillData(data)  {
                if(data !== '') {
                    let _this = this;

                    if('shipping' in data) {
                        _this.parsePrefillBilling(data['shipping']);
                        _this.parsePrefillShipping(data['shipping']);
                    }

                    _this.billingShippingSame = false;

                    setTimeout(function() {
                        if('billing' in data) {
                            _this.parsePrefillBilling(data['billing']);
                        }

                        $('html, body').animate({
                            scrollTop: 1223 //$('.shipping-method-segment').offset().top
                        }, 2000);
                    }, 1500);
/*
                    setTimeout(function() {
                        if('billing' in data) {
                            _this.parsePrefillBilling(data['billing']);
                        }
                    }, 4000);

*/
                }
            },
            email(addy) {
                if(addy === '') {
                    this.shippingEmailError = 'Missing Email';
                }
                else {
                    if(this.validEmail(addy)) {
                        let payload = {
                            method: 'email',
                            value: addy
                        };
                        this.$emit('updated', payload);
                        this.shippingEmailError = '';
                    }
                    else {
                        this.shippingEmailError = 'Invalid Email';
                    }
                }
            },
            shippingPhone(val) {
                if(this.billingShippingSame) {
                    this.billingPhone = val;
                }

                if(val !== '') {
                    if(this.validPhoneNum(val)) {
                        let payload = {
                            method: 'shippingPhone',
                            value: val
                        };
                        this.$emit('updated', payload);
                        this.shippingPhoneError = '';
                    }
                    else {
                        this.shippingPhoneError = 'Invalid Phone';
                        this.shippingValid = false;
                    }
                }
                else {
                    this.shippingPhoneError = 'Missing Phone';
                    this.shippingValid = false;
                }
            },
            billingPhone(val) {
                if(val !== '') {
                    if(this.validPhoneNum(val)) {
                        let payload2 = {
                            method: 'billingPhone',
                            value: val
                        };
                        this.$emit('updated', payload2);
                        this.billingPhoneError = '';
                    }
                    else {
                        this.billingPhoneError = 'Invalid Phone';
                        this.billingValid = false;
                    }
                }
                else {
                    this.billingPhoneError = 'Missing Phone';
                    this.billingValid = false;
                }
            },
            shippingZip(val) {
                if(this.billingShippingSame) {
                    this.billingZip = val;
                }

                if(val !== '') {
                    if(this.validZipCode(val)) {
                        let payload = {
                            method: 'shippingZip',
                            value: val
                        };
                        this.$emit('updated', payload);
                        this.shippingZipError = '';
                    }
                    else {
                        this.shippingZipError = 'Invalid Zip';
                        this.shippingValid = false;
                    }
                }
                else {
                    this.shippingZipError = 'Missing Zip';
                    this.shippingValid = false;
                }
            },
            billingZip(val) {
                if(val !== '') {
                    if(this.validZipCode(val)) {
                        let payload2 = {
                            method: 'billingZip',
                            value: val
                        };
                        this.$emit('updated', payload2);
                        this.billingZipError = '';
                    }
                    else {
                        this.billingZipError = 'Invalid Zip';
                        this.billingValid = false;
                    }
                }
                else {
                    this.billingZipError = 'Missing Zip';
                    this.billingValid = false;
                }
            },

            shippingLine(val) {
                if(this.isNumeric(val)) {
                    this.shippingAmt = val;
                }
            },
            tax(lines) {
                if(this.isNumeric(lines)) {
                    this.shippingAmt = lines;
                }
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

                let payload = {
                    method: 'billingShippingSame',
                    value: flag
                };
                this.$emit('updated', payload)

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
                    this.billingValid = false;
                    let _this = this;
                    setTimeout(function () {
                        _this.billingFirst = '';
                        _this.billingLast = '';
                        _this.billingCompany = '';
                        _this.billingAddress = '';
                        _this.billingApt = ''
                        _this.billingCity = '';
                        _this.billingCountry = 'us';
                        _this.billingState = '';
                        _this.billingZip = '';
                        _this.billingPhone = '';
                    }, 100);

                }
            },

            shippingFirst(val) {
                if(this.billingShippingSame) {
                    this.billingFirst = val;
                }

                if(val === '') {
                    this.shippingFirstError = 'First Name Required';
                }
                else {
                    let payload = {
                        method: 'shippingFirst',
                        value: val
                    };
                    this.$emit('updated', payload);

                    this.shippingFirstError = ''
                }
                this.isShippingValid;
            },
            shippingLast(val) {
                if(this.billingShippingSame) {
                    this.billingLast = val;
                }

                if(val === '') {
                    this.shippingLastError = 'Last name Required';
                }
                else {
                    let payload = {
                        method: 'shippingLast',
                        value: val
                    };
                    this.$emit('updated', payload);

                    this.shippingLastError = ''
                }
                this.isShippingValid;
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
                this.isShippingValid;
            },
            shippingAddress(val) {
                if(this.billingShippingSame) {
                    this.billingAddress = val;
                }

                if(val === '') {
                    this.shippingAddressError = 'Address Required';
                }
                else {
                    let payload = {
                        method: 'shippingAddress',
                        value: val
                    };
                    this.$emit('updated', payload);

                    this.shippingAddressError = ''
                }
                this.isShippingValid;
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
                this.isShippingValid;
            },
            shippingCity(val) {
                if(this.billingShippingSame) {
                    this.billingCity = val;
                }

                if(val === '') {
                    this.shippingCityError = 'City Required';
                }
                else {
                    let payload = {
                        method: 'shippingCity',
                        value: val
                    };
                    this.$emit('updated', payload);

                    this.shippingCityError = ''
                }
                this.isShippingValid;
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
                this.isShippingValid;
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
                this.isShippingValid;
            },
            billingFirst(val) {
                if(val === '') {
                    this.billingFirstError = 'First Name Required';
                }
                else {
                    let payload2 = {
                        method: 'billingFirst',
                        value: val
                    };
                    this.$emit('updated', payload2);

                    this.billingFirstError = '';
                }
                this.isBillingValid
            },
            billingLast(val) {
                if(val === '') {
                    this.billingLastError = 'Last Name Required';
                }
                else {
                    let payload2 = {
                        method: 'billingLast',
                        value: val
                    };
                    this.$emit('updated', payload2);

                    this.billingLastError = '';
                }
                this.isBillingValid
            },
            billingCompany(val) {
                let payload2 = {
                    method: 'billingCompany',
                    value: val
                };
                this.$emit('updated', payload2);
                this.isBillingValid
            },
            billingAddress(val) {
                if(val === '') {
                    this.billingAddressError = 'Address Required';
                }
                else {
                    let payload2 = {
                        method: 'billingAddress',
                        value: val
                    };
                    this.$emit('updated', payload2);
                    this.billingAddressError = ''
                }
                this.isBillingValid
            },
            billingApt(val) {
                let payload2 = {
                    method: 'billingApt',
                    value: val
                };
                this.$emit('updated', payload2);
                this.isBillingValid
            },
            billingCity(val) {
                if(val === '') {
                    this.billingCityError = 'City Required';
                }
                else {
                    let payload2 = {
                        method: 'billingCity',
                        value: val
                    };
                    this.$emit('updated', payload2);
                    this.billingCityError = ''
                }
                this.isBillingValid
            },
            billingCountry(val) {
                let payload2 = {
                    method: 'billingCountry',
                    value: val
                };
                this.$emit('updated', payload2);
                this.isBillingValid
            },
            billingState(val) {
                let payload2 = {
                    method: 'billingState',
                    value: val
                };
                this.$emit('updated', payload2);
                this.isBillingValid
            },

            billingValid(flag) {
                if(flag) {
                    console.log('Valid Billing enabled!')
                    let payload2 = {
                        method: 'billingValid',
                        value: flag
                    };
                    this.$emit('updated', payload2);
                }
                else {
                    console.log('Valid Billing disabled')
                }
            },
            shippingValid(flag) {
                if(flag) {
                    console.log('Valid Shipping enabled!')
                }
                else {
                    console.log('Valid Shipping disabled')
                }

                let payload = {
                    method: 'shippingValid',
                    value: flag
                };

                this.$emit('updated', payload);
            },
            showOneClick(flag) {
                console.log('Toggling 1-Click Mode! - '+ flag);

                let _this = this;
                setTimeout(function() {
                    _this.launchOneClick = flag;
                }, 100);
            }
        },
        data() {
            return {
                launchOneClick: false,
                joinMailingList: true,
                billingShippingSame: true,
                email: '',
                giftCardCode: '',
                showShippingMethods: false,
                selectedPaymentMethod: 'credit',
                subTotal: '1.00',
                total: '1.00',
                shippingAmt: '',
                taxAmt: '',

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
                billingPhone: '',

                shippingEmailError: '',
                shippingPhoneError: '',
                billingPhoneError: '',
                shippingZipError: '',
                billingZipError: '',
                shippingFirstError: '',
                billingFirstError: '',
                shippingLastError: '',
                billingLastError: '',
                shippingAddressError: '',
                billingAddressError: '',
                shippingCityError: '',
                billingCityError: '',

                billingValid: false,
                shippingValid: false
            };
        },
        computed: {
            paymentSectionHeight() {
                if(this.loading) {
                    return {'--pHeight': '100%', '--mHeight': '85%'}
                }
                else if(this.showOneClick) {
                    return {'--pHeight': '80%', '--mHeight': '85%'}
                }
                else {
                    return {'--pHeight': 'auto', '--mHeight': 'auto'}
                }
            },
            typeOfTax() {
                if(typeof this.tax === 'string') {
                    return 'string';
                }
                else {
                    return 'object';
                }
            },
            isBillingValid() {
                let results = false;

                if((this.billingFirst !== '') && (this.billingFirstError === '')) {
                    if((this.billingLast !== '') && (this.billingLastError === '')) {
                        if((this.billingAddress !== '') && (this.billingAddressError === '') ) {
                            if((this.billingCity !== '') && (this.billingCityError === '')) {
                                if(this.billingState !== '') {
                                    if((this.billingZip !== '') && (this.billingZipError === '')) {
                                        if(this.billingCountry !== '') {
                                            if((this.billingPhone !== '') && (this.billingPhoneError === '')) {
                                                this.billingValid = true;
                                                results = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if(!results) {
                    this.billingValid = false;
                }

                return results;
            },
            isShippingValid() {
                let results = false;

                if((this.shippingFirst !== '') && (this.shippingFirstError === '')) {
                    if((this.shippingLast !== '') && (this.shippingLastError === '')) {
                        if((this.shippingAddress !== '') && (this.shippingAddressError === '') ) {
                            if((this.shippingCity !== '') && (this.shippingCityError === '')) {
                                if(this.shippingState !== '') {
                                    if((this.shippingZip !== '') && (this.shippingZipError === '')) {
                                        if(this.shippingCountry !== '') {
                                            if((this.shippingPhone !== '') && (this.shippingPhoneError === '')) {
                                                this.shippingValid = true;
                                                results = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if(!results) {
                    this.shippingValid = false;
                }

                return results;
            },
        },
        methods: {
            isNumeric(n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
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
                /*
                console.log('Calculating total')
                let amt = 0;
                for(let idx in this.lineItems) {
                    amt = amt + (this.lineItems[idx].variant.price * this.lineItems[idx].qty)
                }

                this.total = parseFloat(amt).toFixed(2);
                 */
                return this.pricing.total;
            },
            validPhoneNum(phone) {
                let results = false;
                let ph = libphonenumber.parsePhoneNumber(phone, this.shippingCountry.toUpperCase());

                console.log('Phone Obj for '+phone, ph)

                if(ph !== undefined) {
                    if(ph.isValid())
                    {
                        results = true;
                    }

                }

                return results;
            },
            validEmail(email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            },
            validZipCode(zip) {
                let valid = postalCodes.validate(this.shippingCountry, zip);

                console.log(`Testing Zip Code - ${zip}`, [valid]);
                return valid === true;
            },
            parsePrefillShipping(ship) {
                for(let col in ship) {
                    switch(col) {
                        case 'address':
                            this.shippingAddress = ship[col];
                            break;

                        case 'apt':
                            this.shippingApt = ship[col];
                            break;

                        case 'city':
                            this.shippingCity = ship[col];
                            break;

                        case 'company':
                            this.shippingCompany = ship[col];
                            break;

                        case 'country':
                            if(ship[col] === null) {
                                this.shippingCountry = 'us';
                            }
                            else {
                                this.shippingCountry = ship[col];
                            }

                            break;

                        case 'first_name':
                            this.shippingFirst = ship[col];
                            break;

                        case 'last_name':
                            this.shippingLast = ship[col];
                            break;

                        case 'phone':
                            this.shippingPhone = ship[col];
                            break;

                        case 'state':
                            this.shippingState = ship[col];
                            break;

                        case 'zip':
                            this.shippingZip = ship[col];
                            break;
                    }
                }
            },
            parsePrefillBilling(bill) {
                for(let col in bill) {
                    switch(col) {
                        case 'address':
                            this.billingAddress = bill[col];
                            break;

                        case 'apt':
                            this.billingApt = bill[col];
                            break;

                        case 'city':
                            this.billingCity = bill[col];
                            break;

                        case 'company':
                            this.billingCompany = bill[col];
                            break;

                        case 'country':
                            if(bill[col] === null) {
                                this.billingCountry = 'us';
                            }
                            else {
                                this.billingCountry = bill[col];
                            }
                            break;

                        case 'first_name':
                            this.billingFirst = bill[col];
                            break;

                        case 'last_name':
                            this.billingLast = bill[col];
                            break;

                        case 'phone':
                            this.billingPhone = bill[col];
                            break;

                        case 'state':
                            this.billingState = bill[col];
                            break;

                        case 'zip':
                            this.billingZip = bill[col];
                            break;
                    }
                }
            }
        },
        mounted() {
            let payload2 = {
                method: 'billingCountry',
                value: this.billingCountry
            };
            this.$emit('updated', payload2);


            payload2.method = 'shippingCountry';
            this.$emit('updated', payload2);
            /*
            this.calculateSubTotal();
            this.calculateTotal();

            let payload = {
                method: 'shippingCountry',
                value: this.shippingCountry
            };
            this.$emit('updated', payload);

            payload.method = 'billingCountry'
            this.$emit('updated', payload);
             */
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
        #checkoutApp {
            height: 100%;
        }

        .slide-enter-active, .slide-leave-active {
            transition: margin-bottom .8s ease-out;
        }

        .slide-enter, .slide-leave-to {
            margin-bottom: -200px;
        }

        .slide-enter-to, .slide-leave {
            margin-bottom: 0px;
        }

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
        .payment-section {
            height: var(--mHeight);
        }
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
            height: var(--pHeight);
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
        }

        .inner-payment-section.loading .inner-loading-piece {
            display: flex;
            flex-flow: column;
            justify-content: center;
            height: 100%;
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
