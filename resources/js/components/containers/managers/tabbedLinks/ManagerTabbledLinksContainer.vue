<template>
    <tabbed-links-screen
        :loading="loading"
        :links="availableLinks"
        :active-link="activeLink"
    ></tabbed-links-screen>
</template>

<script>
import TabbedLinksScreen from "../../../presenters/managers/tabbedLinks/ManagerTabbedLinksScreen";

import { mapActions, mapGetters, mapState } from 'vuex';

export default {
    name: "ManagerTabbedLinksContainer",
    components: {
        TabbedLinksScreen
    },
    props: ['type'],
    watch: {
        availableLinks(links) {
            if(links.length > 0) {
                for(let link in links) {
                    if(links[link].active) {
                        this.activeLink = link;
                    }
                }
            }
        }
    },
    data() {
        return {
            activeLink: ''
        };
    },
    computed: {
        ...mapState({
            loading (state, getters) {
                return getters[this.type+'/tabbedLinksAreLoading']
            },
            availableLinks (state, getters) {
                return getters[this.type+'/tabbedLinks']
            }
        }),
    },
    methods: {
        ...mapActions({
            initLinks (dispatch) {
                dispatch(this.type+'/fetchTabbedLinks');
            }
        }),
    },
    mounted() {
        this.initLinks();
        console.log('ManagerTabbedLinksContainer mounted!')
    }
}
</script>

<style scoped>

</style>
