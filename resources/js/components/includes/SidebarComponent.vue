<template>
    <div class="leadbinder-sidebar-container">
        <div class="main-list">
            <ul v-if="isInternalUser">
                <li><a @click="switchMerchants()"><i class="fad fa-users"></i><span>Merchants</span></a></li>
                <form method="POST" action="switch" class="merchant-switch">
                    <input type="hidden" name="_token" :value="csrf">
                </form>
            </ul>
            <ul v-if="!allCommerceIsActive">
                <li><a href="merchandise"><i class="fad fa-inventory"></i><span>Merchandise</span></a></li>
            </ul>
        </div>
        <div class="intentional-empty-space"></div>
        <div class="button-panel">
            <button type="button" @click="redirectTo('logout')"><i class="fad fa-sign-out-alt fa-flip-horizontal"></i></button>
            <button type="button" @click="redirectTo('settings')"><i class="fad fa-cog"></i></button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SidebarComponent",
        props: ['isInternalUser', 'internalUuid', 'activeUuid'],
        data() {
            return {
                selectedVal: '',
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        computed: {
            isRoleAdmin() {
                let valid = '';

                switch(this.role) {
                    case 'dev':
                    case 'dev-god':
                    case 'platform-admin':
                    case 'platform-user':
                        valid = true;
                        break;

                    case 'merchant-owner':
                    case 'merchant-api-user':
                    default:
                        valid = false;
                }

                return valid;
            },
            allCommerceIsActive() {
                let results = false;

                if(this.internalUuid != null) {
                    results = this.internalUuid === this.activeUuid;
                }

                return results;
            }
        },
        methods: {
            redirectTo($route) {
                window.location.href = $route;
            },
            switchMerchants() {
                $('.merchant-switch').submit();
            }
        }
    }
</script>

<style scoped>
    @media screen {
        .leadbinder-sidebar-container {
            color: #fff;
            margin-left: 1em;
        }

        a {
            color: #fff;
            text-decoration: none;
        }
    }

    @media screen and (max-width: 999px) {
        .leadbinder-sidebar-container {
            display: none;
        }
    }

    @media screen and (min-width: 1000px) {
        .leadbinder-sidebar-container {
            padding: 0.25em;
            margin-left: 2em;
        }

        ul {
            padding-left: 0;
        }

        li {
            list-style-type: none;
        }

        a {
            font-size: 1.5em;
            transition: color 0.5s ease;
        }

        a:hover {
            color: #DFF200;
        }

        a span {
            margin-left: 0.5em;
        }

        .intentional-empty-space {
            height: 25em;
        }

        button {
            background-color: transparent;
            border: 1px solid #fff;
            border-radius: 0.25em;
            margin-right: 0.25em;
            transition: background-color 0.5s ease;
        }

        button:hover {
            background-color: #DFF200;
            color: #fff;
            border: 1px solid black;
        }

        button i {
            font-size: 3em;
            padding: 0.25em;
            color: #fff;
        }

        button i:hover {
            color: #000;
        }
    }
</style>
