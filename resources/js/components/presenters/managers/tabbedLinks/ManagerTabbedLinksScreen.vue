<template>
    <div class="tabbed-links">
        <div class="inner-tabbed-links">
            <div class="loading-segment" v-if="loading">
                <div class="inner-loading-segment">
                    <p><i class="fad fa-satellite-dish faa-float animated"></i><span style="padding-left: 1em;">Loading Links....</span></p>
                </div>
            </div>
            <div class="links-segment" v-else>
                <div class="inner-links-segment">
                    <div class="links">
                        <div class="inner-links">
                            <p class="link-label">Jump To: </p>
                            <div class="link" v-for="(link, idx) in links" :class="renderActiveLink(link.active)">
                                <div class="inner-link">
                                    <a class="link-btn" v-if="link.url !== ''" :href="link.url">{{ link.title }}</a>
                                    <p class="link-btn" v-else>{{ link.title }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="inner-mobile-links">
                            <select v-model="selectedLink">
                                <option value="">Jump To</option>
                                <option v-for="(link, idx) in links" :value="idx">{{ link.title }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ManagerTabbedLinksScreen",
    props: ['loading', 'links', 'activeLink'],
    watch: {
        activeLink(idx) {
            this.selectedLink = idx;
        },
        selectedLink(idx) {
            if(idx !== '') {
                if(this.links[idx].url !== '') {
                    window.location.href = this.links[idx].url;
                }
            }
        }
    },
    data() {
        return {
            selectedLink: ''
        }
    },
    methods: {
        renderActiveLink(flag) {
            let results = '';

            if(flag) {
                results = 'active-tabbed-link';
            }

            return results;
        }
    },
}
</script>

<style scoped>
    @media screen {
        .links {
            width: 100%;
        }

        .inner-links {
            display: flex;
            flex-flow: row;
            align-items: flex-start;
        }

        .link {
            border-radius: 2em;
            width: 15%;
            margin-left: 1em;
        }

        .link-label {
            padding-right: 1em;
        }

        .active-tabbed-link {
            background-color: #3490dc;
            color: #fff;
            cursor: pointer;
        }

        .inner-links {
            text-align: center;
        }

        .active-tabbed-link .link-btn {
            color: #fff;

        }

        .active-tabbed-link .link-btn:hover {
            color: #000;
        }

        .c-dark-theme .active-tabbed-link .link-btn {
            color: #000;
        }

        .c-dark-theme .active-tabbed-link .link-btn:hover {
            color: #fff;
        }
    }

    @media screen and (max-width: 999px) {
        .inner-links {
            display: none;
        }

        .inner-mobile-links {
            margin: 0 10%;
        }

        .inner-mobile-links select {
            width: 100%;
            padding-left: 2.5%;
        }
    }

    @media screen and (min-width: 1000px) {
        .inner-mobile-links {
            display: none;
        }
    }
</style>
