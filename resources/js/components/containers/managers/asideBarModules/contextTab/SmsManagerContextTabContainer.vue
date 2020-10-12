<template>
    <context-tab
        @content-switch="contentSwitch"
    ></context-tab>
</template>

<script>
    import ContextTab
        from "../../../../presenters/managers/asideBarModules/contextTab/SmsManagerContextTabScreen";

    import { mapMutations } from 'vuex';

    export default {
        name: "SmsManagerContextTabContainer",
        components: {
            ContextTab
        },
        methods: {
            ...mapMutations({
                setClientView: 'smsManager/clientContentView'
            }),
            contentSwitch(flag) {
                if(flag) {
                    // Toggle Reports
                    this.setClientView('providers')
                }
                else {
                    // Toggle Configs
                    this.setClientView('reports')
                }
            }
        }
    }
</script>

<style scoped>

</style>
