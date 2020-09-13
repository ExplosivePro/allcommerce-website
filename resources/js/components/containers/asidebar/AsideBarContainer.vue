<template>
    <aside-bar-screen
        :username="username"
        :profile-img="profileImg"
    ></aside-bar-screen>
</template>

<script>
import AsideBarScreen from "../../presenters/asidebar/AsideBarComponent";

import { mapGetters } from 'vuex';

export default {
    name: "AsideBarContainer",
    components: {
        AsideBarScreen
    },
    props: ['username', 'profileImg'],
    computed: {
        ...mapGetters({
            getActiveContextTabComponent: 'asidebar/getActiveContextTabComponent'
        })
    },
    methods: {

    },
    mounted() {
        console.log('Aside Bar mounted!');
    }
}
</script>

<style scoped>

</style>
