<template>
    <section>
        <polaris-empty-state
            heading="Your journey awaits."
            :action="{content: 'Connect Account', onAction: () => { $emit('trigger-connect-modal', true) } }"

            image="https://cdn.shopify.com/shopifycloud/web/assets/v1/dd646441fcb32ed84d99e20aa8b13ac8.svg">
            <p>All you have to do is to connect. Link your AllCommerce Account now.</p>
        </polaris-empty-state>
        <sweet-modal ref="modal"
                     title="<span class='ac-font2'>Allcommerce</span> - Merchant Log In"
                     :enable-mobile-fullscreen="true"
                     modal-theme="dark"
                     @close="() => { $emit('trigger-connect-modal', false) }"
            >
            <polaris-vue-fade-up-transition>
                <polaris-banner
                    v-if="showError"
                    :title="errorTitle"
                    icon="&lt;svg class=&quot;Polaris-Icon__Svg&quot; viewBox=&quot;0 0 20 20&quot;&gt;&lt;path d=&quot;M2 10c0-1.846.635-3.543 1.688-4.897l11.209 11.209A7.954 7.954 0 0 1 10 18c-4.411 0-8-3.589-8-8m14.312 4.897L5.103 3.688A7.954 7.954 0 0 1 10 2c4.411 0 8 3.589 8 8a7.952 7.952 0 0 1-1.688 4.897M0 10c0 5.514 4.486 10 10 10s10-4.486 10-10S15.514 0 10 0 0 4.486 0 10&quot; fill-rule=&quot;evenodd&quot;&gt;&lt;/path&gt;&lt;/svg&gt;"
                    status="critical">
                    <p>{{ errorMsg }}</p>
                </polaris-banner>
            </polaris-vue-fade-up-transition>

            <div class="modal-content">
                <div class="inner-modal-content">
                    <div class="modal-title">
                        <div class="inner-modal-title">
                            <div class="modal-title-logo-bit">
                                <div class="inner-logo">
                                    <img :src="icon">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="actual-modal-content">
                        <div class="inner-actual-content">
                            <polaris-form-layout>
                                <polaris-text-field
                                    label="Account email"
                                    v-model="username"
                                    :error="emailError"
                                    type="email"></polaris-text-field>
                                <polaris-text-field
                                    label="Account password"
                                    v-model="password"
                                    :error="passwordError"
                                    type="password"
                                ></polaris-text-field>
                                <polaris-button primary @click="modalSubmit()" v-if="!showLoadingWheel">
                                    <span style="text-transform: uppercase;font-weight: bold;">Log In</span>
                                </polaris-button>
                                <polaris-spinner v-else
                                                 size="large"
                                                 color="teal"></polaris-spinner>
                            </polaris-form-layout>
                        </div>
                    </div>
                </div>
            </div>

        </sweet-modal>
    </section>
</template>

<script>
    import { SweetModal } from 'sweet-modal-vue'

    export default {
        name: "linkAccountComponent",
        components: { SweetModal },
        props: ['showModal', 'showLoadingWheel', 'showError', 'errorTitle', 'errorMsg', 'icon'],
        data() {
            return {
                username: '',
                password: '',
                emailError: false,
                passwordError: false
            };
        },
        watch: {
            showModal(flag) {
                if(flag) {
                    this.$refs.modal.open()
                }
            }
        },
        methods: {
            modalSubmit() {
                this.emailError = false;
                this.passwordError = false;
                if(this.username === '') {
                    this.emailError = 'Missing username';
                }
                else if(this.password === '') {
                    this.passwordError = 'Missing Password'
                }
                else {
                    this.$emit('trigger-submit', {
                        username: this.username,
                        password: this.password,
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>

<style>
    @media screen {
        .sweet-modal.theme-dark {
            border: 5px solid #43467D;
        }
        .sweet-modal.theme-dark .sweet-title {
            border-bottom-width:2px;
            border-bottom-color: #43467D;
            box-shadow: 0px 1px 0px #273442;
            background-color: #2D2D2D;
            text-align: center;
        }

        .sweet-content {
            background-color: #2D2D2D
        }

        .modal-content {
            width: 100%;
        }

        .inner-modal-content {
            display: flex;
            flex-flow: column;
        }

        .modal-title {
            width: 100%;
        }

        .inner-modal-title {
            display: flex;
            flex-flow: column;
            justify-content: center;
            align-items:center
        }

        .modal-title-logo-bit {
            width: 100%;
        }

        .inner-logo {
            margin: 0 40%;
        }

        .inner-logo img {
            width: 100%;
        }

        .modal-title-text-bit {
            width: 100%;
        }

        .inner-text {
            text-align: center;
        }

        .ac-font {
            font-family: semplicitapro, sans-serif;
            font-weight: 700;
            font-style: normal;
            font-size: 3em;
            color: #fff;
            margin-top: 0;
        }

        .ac-font2 {
            font-family: semplicitapro, sans-serif;
            font-weight: 700;
            font-style: normal;
            color: #fff;
        }

        .actual-modal-content {
            margin: 0 15% 5%;
        }

        .inner-actual-content {
            display: flex;
            flex-flow: column;
            justify-content: center;
        }

        .Polaris-FormLayout__Item {
            text-align: center;
        }

        .Polaris-Label__Text {
            text-transform: uppercase;
            font-weight: bold;
        }

        .Polaris-Heading, .Polaris-Banner__Content p {
            color: #000;
        }
    }

    @media screen and (max-width: 999px) {
        .sweet-content {
            height: 100%;
        }
    }

    @media screen and (min-width: 1000px) {

    }
</style>
