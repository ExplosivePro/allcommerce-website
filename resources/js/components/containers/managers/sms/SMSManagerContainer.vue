<template>
    <sms-manager-screen
        :view-mode="viewMode"
        :height="contentHeight"
    ></sms-manager-screen>
</template>

<script>
import SmsManagerScreen from "../../../presenters/managers/sms/SMSManagerScreen";
import { mapGetters } from 'vuex';

export default {
    name: "SmsManagerContainer",
    components: {
        SmsManagerScreen
    },
    props: ['client', 'merchant'],
    watch: {
        screenHeight(h) {
            console.log('Watching screenHeight update to ' +h);
            this.contentHeight['--sHeight']  = this.desktopScreenHeight+'px';
            this.contentHeight['--msHeight'] = this.mobileScreenHeight+'px';
        }
    },
    data() {
        return {
            contentHeight: {
                '--sHeight': this.desktopScreenHeight+'px',
                '--msHeight': this.mobileScreenHeight+'px',
            }
        };
    },
    computed: {
        ...mapGetters({
            screenWidth: 'screenWidth',
            screenHeight: 'screenHeight',
        }),
        mobileScreenHeight() {
            let h = (this.screenHeight * 0.65);

            if(h > 550) {
                h = (this.screenHeight * 0.725);
            }

            return h
        },
        desktopScreenHeight() {
            let h = (this.screenHeight * 0.675);

            /*
            if(h > 550) {
                h = (this.screenHeight * 0.725);
            }
            */

            return h
        },
        viewMode() {
            let mode = 'client';
            if(this.merchant !== undefined) {
                mode = 'merchant';
            }

            return mode;
        }
    },
    mounted() {
        this.contentHeight = {
            '--sHeight': this.desktopScreenHeight+'px',
            '--msHeight': this.mobileScreenHeight+'px',
        }

        console.log('SMSManagerContainer mounted!', [{client: this.client, merchant: this.merchant}]);
    }
}
</script>

<style>
    @media screen {

    }

    @media screen and (max-width: 999px) {
        .c-main .content {
            height: auto; /*var(--msHeight)*/;
        }
    }

    @media screen and (min-width: 1000px) {}
</style>

<style scoped>
    @media screen {}
    @media screen and (max-width: 999px) {}
    @media screen and (min-width: 1000px) {}
</style>
