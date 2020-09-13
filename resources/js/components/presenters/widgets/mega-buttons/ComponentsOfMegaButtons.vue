<template>
    <div class="mega-button-grid-container">
        <div class="inner-mega-button-grid">
            <div class="mega-content">
                <div class="inner-mega-content">
                    <div class="loading-piece" v-if="loading">
                        <div class="inner-loading-piece">
                            <sexy-hurricane-loading
                                loading-msg="Loading Reporting Channels..."
                            ></sexy-hurricane-loading>
                        </div>
                    </div>
                    <div class="error-piece" v-else-if="(!loading) && (error !== '')">
                        <div class="inner-error-piece">
                            <p>{{ error }}</p>
                        </div>
                    </div>
                    <div class="content-piece" v-else>
                        <div class="inner-content-piece">
                            <div class="button-grid">
                                <div class="inner-button-grid">
                                    <div class="mega-button-cell" v-for="(buttonDeets, idx) in buttons">
                                        <div class="inner-cell">
                                            <mega-button
                                                :text="buttonDeets.text"
                                                :image="buttonDeets.image"
                                                :click-id="buttonDeets.id"
                                                @mega-click="megaButtonClicked"
                                            ></mega-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MegaButton from "./MegaButton";
    import SexyHurricaneLoading from '../loading/SexyHurricane';
    export default {
        name: "ComponentsOfMegaButtons",
        components: {
            MegaButton,
            SexyHurricaneLoading,
        },
        props: ['loading', 'error', 'buttons'],
        methods: {
            megaButtonClicked(clickId) {
                this.$emit('mega-click', clickId);
            }
        }
    }
</script>

<style scoped>
@media screen {
    .mega-button-grid-container {
        background-color: lightgray;
        border-radius: 0.2em;
    }

    .c-dark-theme .mega-button-grid-container {
        background-color: #1e1e29;
    }

    .inner-mega-button-grid {
        padding: 2%;
    }

    .mega-content {
        width: 100%;
        height: 100%;
    }

    .inner-mega-content
    {
        background-color: #fff;
        border-radius: 0.2em;
        position:relative;
        -webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        -moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;

        padding: 5% 2.5%;
    }
    .inner-mega-content:before, .inner-mega-content:after
    {
        content:"";
        position:absolute;
        z-index:-1;
        -webkit-box-shadow:0 0 20px rgba(0,0,0,0.8);
        -moz-box-shadow:0 0 20px rgba(0,0,0,0.8);
        box-shadow:0 0 20px rgba(0,0,0,0.8);
        top:0;
        bottom:0;
        left:10px;
        right:10px;
        -moz-border-radius:100px / 10px;
        border-radius:100px / 10px;
    }
    .inner-mega-content:after
    {
        right:10px;
        left:auto;
        -webkit-transform:skew(8deg) rotate(3deg);
        -moz-transform:skew(8deg) rotate(3deg);
        -ms-transform:skew(8deg) rotate(3deg);
        -o-transform:skew(8deg) rotate(3deg);
        transform:skew(8deg) rotate(3deg);
    }

    .c-dark-theme .inner-mega-content {
        background-color: #2c2c34;
    }

    .content-piece {
        width: 100%;
        height: 100%;
    }

    .inner-content-piece {

    }

    .button-grid {
        width: 100%;
        height: 100%;
    }

    .inner-button-grid {
        display: flex;
    }
}

@media screen and (max-width: 999px) {
    h2, td, th {
        font-size: 60%;
    }

    .inner-button-grid {
        flex-flow: row wrap;
    }

    .mega-button-cell {
        width: 50%;
    }

    .inner-cell {
        margin: 0 5%;
    }
}

@media screen and (min-width: 1000px) {
    h2, td, th {
        font-size: 80%;
    }

    .inner-button-grid {
        flex-flow: row wrap;
    }

    .mega-button-cell {
        width: 50%;
    }

    .inner-cell {
        margin: 0 5%;
    }
}
</style>
