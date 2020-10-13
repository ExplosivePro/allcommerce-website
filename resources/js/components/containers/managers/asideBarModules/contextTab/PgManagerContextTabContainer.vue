<template>
    <context-tab
        @content-switch="contentSwitch"
    ></context-tab>
</template>

<script>
    import ContextTab
        from "../../../../presenters/managers/asideBarModules/contextTab/PgManagerContextTabScreen";

    import { mapMutations } from 'vuex';

    export default {
        name: "PgManagerContextTabContainer",
        components: {
            ContextTab
        },
        methods: {
            ...mapMutations({
                setClientView: 'paymentGatewaysManager/clientContentView'
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
