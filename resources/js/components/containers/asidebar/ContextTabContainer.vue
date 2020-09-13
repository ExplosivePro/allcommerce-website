<template>
    <context-tab
        :title="title"
        :tab-component="activeTab"
    ></context-tab>
</template>

<script>
import ContextTab from "../../presenters/asidebar/ContextTabComponent";

import { mapState } from 'vuex';

export default {
    name: "ContextTabComponent",
    components: {
        ContextTab
    },
    data() {
        return {

        };
    },
    computed: {
        ...mapState('asidebar/contextTab', ['activeTab', 'title'])
    },
    methods: {
        testFn() {
            console.log('Default component ' + this.activeTab);
        }
    },
    mounted() {
        console.log('Context Tab mounted!')
        this.testFn();
    }

}
</script>

<style scoped>

</style>
