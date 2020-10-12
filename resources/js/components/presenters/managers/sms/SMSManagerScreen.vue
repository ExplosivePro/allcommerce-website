<template>
    <div class="sms-manager" :style="height">
        <div class="inner-sms-manager">
            <div class="tabbed-linked-section">
                <div class="inner-tabbed-linked-section">
                    <tabbed-links type="smsManager"></tabbed-links>
                </div>
            </div>
            <div class="view-mode-section">
                <div class="inner-view-mode-section">
                    <client-manager v-if="viewMode === 'client'"></client-manager>
                    <merchant-manager v-else-if="viewMode === 'merchant'"></merchant-manager>
                    <div class="view-mode-error" v-else>
                        <div class="inner-view-mode-error">
                            <i class="fad fa-bomb" :class="errorIconClass" @click="toggleErrorIcon"></i>
                            <p> Better watch out. You could find yourself in a minefield!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import TabbedLinks from "../../../containers/managers/tabbedLinks/ManagerTabbledLinksContainer";
    import ClientManager from "../../../containers/managers/sms/SMSClientManagerContainer";
    import MerchantManager from "../../../containers/managers/sms/SMSMerchantManagerContainer";

    export default {
        name: "SMSManagerScreen",
        components: {
            ClientManager,
            MerchantManager,
            TabbedLinks
        },
        props: ['viewMode', 'height'],
        data() {
            return {
                toggleErrorAnimation: false
            };
        },
        computed: {
            errorIconClass() {
                let iconClass = '';

                if(this.toggleErrorAnimation) {
                    iconClass = 'faa-flash animated';
                }

                return iconClass;
            }
        },
        methods: {
            toggleErrorIcon() {
                this.toggleErrorAnimation = !this.toggleErrorAnimation;
            }
        },
    }
</script>

<style scoped>
    @media screen {
    .sms-manager {
        /* height: 100%; */
        width: 100%;
    }

    .inner-sms-manager {
        height: 100%;
        display: flex;
        flex-flow: column;
        justify-content: center;
        align-items: center;
    }

    .tabbed-linked-section {
        height: 7.5%;
        width: 100%;
        border-top-left-radius: 0.25em;
        border-top-right-radius: 0.25em;
        background-color: #fff;
        border: 1px solid #004;
    }

    .c-dark-theme .tabbed-linked-section {
        background-color: #2c2c34;
        border-color: transparent;
    }

    .inner-tabbed-linked-section {
        height: 100%;
        display: flex;
        flex-flow: column;
        justify-content: center;
        margin: 0 5% 0 2.5%;
    }

    .view-mode-section {
        height: 92.5%;
        width: 100%;
        background-color: #3c4b64;
    }

    .c-dark-theme .view-mode-section {
        background-color: #1e1e29;
    }

    .inner-view-mode-section {
        height: 100%;
        display: flex;
        flex-flow: column;
        justify-content: flex-start;
        align-items: center;
    }

    .view-mode-error {
        width: 100%;
        height: 100%;
    }

    .inner-view-mode-error {
        height: 100%;
        display: flex;
        flex-flow: column;
        justify-content: center;
        text-align: center;
    }
}

    @media screen and (max-width: 999px) {
        .sms-manager {
            height: var(--msHeight);
        }

        .inner-sms-manager {
            margin: 0 3%;
        }

        .inner-view-mode-section {
            text-align: center;
        }

        .inner-view-mode-error i {
            font-size: 3em;

        }

        .inner-view-mode-error p {
            font-size: 1em;
            padding: 1em 0;
        }
    }

    @media screen and (min-width: 1000px) {
        .sms-manager {
            height: var(--sHeight);
        }

        .inner-view-mode-error i {
            font-size: 5em;

        }

        .inner-view-mode-error p {
            font-size: 1.75em;
            padding: 1em 0;
        }
    }
</style>
