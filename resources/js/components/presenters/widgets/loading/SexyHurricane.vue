<template>
    <div class="spinny-loader">
        <div class="center-wrapper">
            <i class="fad fa-galaxy fa-spin"></i>
            <p>{{ msg }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SexyHurricaneLoaderComponent",
        props: ['loadingMsg'],
        data() {
            return {
                msg: 'Loading...'
            }
        },
        mounted() {
            if((this.loadingMsg !== '') && (this.loadingMsg !== undefined)) {
                this.msg = this.loadingMsg;
            }
        }
    }
</script>

<style scoped>
    .spinny-loader {
        text-align: center;
    }

    .spinny-loader i {
        font-size: 3em;
    }

    .spinny-loader p {
        margin-top: 0.5em;
        font-weight: thin;
        font-size: 1.25em;
        text-transform: uppercase;
        letter-spacing: 0.1em
    }
</style>
