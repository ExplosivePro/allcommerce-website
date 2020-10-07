<template>
    <div class="one-step-section" style="display:none">
        <div class="inner-one-step-section">
            <div class="sms-form">
                <div class="inner-sms-form">
                    <div class="title-segment">
                        <div class="inner-title-segment" v-if="!success">
                            <p class="upper-text" v-if="(!failed)"><b>Welcome back! Ready to Checkout Instantly?</b></p>
                            <p class="upper-text" v-if="(failed)"><b>Ouch! Sorry that didn't work out..</b></p>
                        </div>
                        <div class="inner-title-segment" v-if="success">
                            <p class="upper-text"><b>Checkout Code Validated!</b></p>
                            <p class="upper-text"><b>Welcome back, {{ customer }}!</b></p>
                        </div>
                    </div>

                    <div class="code-segment">
                        <div class="inner-code-segment" v-if="!success">
                            <input type="number" v-model="dataCode" min="0" max="4" placeholder="* * * *" :disabled="loading" v-show="(!loading) && (!failed)"/>
                            <h1 v-if="loading"><i class="fad fa-spinner-third fa-spin"></i></h1>
                            <h1 v-if="failed"><i class="fad fa-spider-black-widow"></i></h1>
                            <h1 v-if="failed">Verification Failed. Please Continue as Guest.</h1>
                        </div>
                        <div class="inner-code-segment success" v-if="success">
                            <h1><i class="fad fa-check-circle" style="color: green; display: none;font-size: 15em;"></i></h1>
                        </div>
                    </div>

                    <div class="subtitle-segment">
                        <div class="inner-subtitle-segment" v-if="(!success)">
                            <p v-if="(error !== '') && (!failed)" class="error-msg"><i class="fal fa-exclamation-circle"></i> {{ error }}</p>
                            <small class="upper-text subtext" v-if="(!failed)">Enter the special code sent to your phone ending in <b>{{ last4 }}</b>.</small>
                        </div>
                        <div class="inner-subtitle-segment" v-if="success">
                            <small class="upper-text subtext">We're setting up your session! Hang tight just a sec!</small>
                            <br />
                            <p><i class="fad fa-spinner-third fa-spin" style="font-size: 2em;"></i></p>
                        </div>
                    </div>

                    <div class="resend-code-segment">
                        <div class="inner-resend-code-segment" v-if="(!success)">
                            <p class="pretend-button" v-if="(!failed) && (allowResets)" @click="resetForm()">Resend Code</p>
                        </div>
                    </div>

                    <div class="continue-as-guest-segment">
                        <div class="inner-continue-as-guest-segment" v-if="(!success)">
                            <p class="pretend-button" @click="unshowForm()">Continue As Guest</p>
                        </div>
                    </div>

                    <div class="empty-space-segment"></div>

                    <div class="return-to-cart-segment">
                        <div class="inner-return-to-cart-segment" v-if="(!success)">
                            <p class="pretend-button" @click="goBack()"><i class="fas fa-chevron-left"></i> Return to Cart</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "DefaultExperienceOneStep",
        props: [
            'last4', 'showForm', 'loading', 'error',
            'failed', 'allowResets', 'success', 'customer'
        ],
        watch: {
            showForm(flag) {
                if(flag) {
                    $('.one-step-section').slideDown();
                }
                else {
                    $('.one-step-section').slideUp();
                }
            },
            dataCode(code) {
                if(code.length > 4) {
                    this.dataCode = code.slice(0, -1)
                }

                if(code.length === 4) {
                    this.$emit('submit-form', code);
                }
            },
            error(msg) {
                if(msg !== '') {
                    this.dataCode = ''
                }
            },
            success(flag) {
                console.log('Authentication success? '+ flag)
                if(flag) {
                    let _this = this;
                    //  slide down a fat green success icon
                    setTimeout(function() {
                        $('.fad.fa-check-circle').slideDown();
                    }, 100);

                    setTimeout(function() {
                        _this.unshowForm()

                    }, 2500);
                }
            }
        },
        data() {
            return {
                dataCode: ''
            };
        },
        methods: {
            unshowForm() {
                $('.one-step-section').slideUp();

                let _this = this;
                setTimeout(function() {
                    _this.$emit('unshow-form');
                }, 1000);
            },
            resetForm() {
                this.$emit('reset-form');
            },
            goBack() {
                window.history.back();
            }
        }
    }
</script>

<style>
    @media screen {
        #checkoutApp {
            height: 100%;
        }

        .one-click-piece {
            width: 100%;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    }

</style>

<style scoped>
    @media screen {
        .one-step-section {
            height: 100%;
            width: 100%;
        }

        .inner-one-step-section {
            height: 100%;

            display: flex;
            flex-flow: column;
            justify-content: center;
            align-items: center;
        }

        .pretend-button {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            margin-top: 1em;
        }

        .sms-form {
            background-color: ivory;
            border: 2px solid #000;
            border-radius: 0.75em;
        }

        .inner-sms-form {
            display: flex;
            flex-flow: column;
            text-align: center;
        }

        .upper-text {
            margin-bottom: 1em;
        }

        .inner-code-segment input {
            width: 60%;
            margin-bottom: 0.5em;
            font-size: 2em;
            text-align: center;
            letter-spacing: 0.1em;
            padding: 2%;
        }

        .inner-code-segment i {
            font-size: 5em;
            padding: 5%;
        }

        .error-msg {
            color: red;
        }
    }

    @media screen and (max-width: 999px) {
        .inner-sms-form {
            padding: 5%;
        }
    }

    @media screen and (min-width: 999px) {
        .sms-form {
            width: 40%;
        }

        .inner-sms-form {
            padding: 15%;
        }
    }
</style>
