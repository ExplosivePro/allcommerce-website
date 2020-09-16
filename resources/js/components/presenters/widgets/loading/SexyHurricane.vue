<template>
    <div class="spinny-loader">
        <div class="center-wrapper">
            <i :class="icon" class="fa-spin" :style="{'--icon-size':iconSize}"></i>
            <p>{{ msg }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SexyHurricaneLoaderComponent",
        props: ['loadingMsg', 'overrideIcon', 'overrideIconSize'],
        data() {
            return {
                msg: 'Loading...',
                icon: 'fad fa-galaxy',
                iconSize: '3em'
            }
        },
        mounted() {
            if((this.loadingMsg !== '') && (this.loadingMsg !== undefined)) {
                this.msg = this.loadingMsg;
            }

            if(this.overrideIcon !== undefined) {
                if(this.overrideIcon !== '') {
                    this.icon = this.overrideIcon
                }
            }

            if(this.overrideIconSize !== undefined) {
                if(this.overrideIconSize !== '') {
                    this.iconSize = this.overrideIconSize
                }
            }
        }
    }
</script>

<style scoped>
    .spinny-loader {
        text-align: center;
    }

    .spinny-loader i {
        font-size: var(--icon-size);
    }

    .spinny-loader p {
        margin-top: 0.5em;
        font-weight: thin;
        font-size: 1.25em;
        text-transform: uppercase;
        letter-spacing: 0.1em
    }
</style>
