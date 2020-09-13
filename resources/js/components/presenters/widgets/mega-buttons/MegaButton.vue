<template>
    <div class="mega-button-container">
        <div class="inner-mega-button">
            <button @click="$emit('mega-click', clickId)">
                <div class="button-img">
                    <div class="inner-button-img">
                        <img :src="image" :alt="text" width="75" height="75">
                    </div>
                </div>
                <div class="button-text">
                    <div class="inner-button-text">
                        <h2>{{ text }}</h2>
                    </div>
                </div>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MegaButton",
        props: ['text', 'image', 'clickId']
    }
</script>

<style scoped>
    @media screen {
        .mega-button-container {
            width: 100%;
            height: 100%;
        }

        .inner-mega-button {
            padding: 2.5%;
            text-align: center;
        }

        button {
            width: 100%;
            height: 100%;

            border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
            border: 0;
            box-shadow: 0 1px 1px 0 rgba(60,75,100,.14), 0 2px 1px -1px rgba(60,75,100,.12), 0 1px 3px 0 rgba(60,75,100,.2);
            padding: 10% 0;
            background-color: lightgray;
        }

        .c-dark-theme button {
            background-color: #20202a;
            border: 1px solid #181924;
            box-shadow: none;
        }

        .inner-button-text {
            margin-top: 5%;
        }

        .inner-button-text h2 {
            font-weight: 700;
            letter-spacing: 0.1em;
        }
    }

    @media screen and (max-width: 999px) {
        button {
            display: flex;
            flex-flow: row;
            padding: 5% 0;
        }

        button .button-img {
            width: 25%;
        }

        button .button-img img {
            height: 1em;
            width: 1em;
        }

        button .button-text {
            width: 75%;
        }

        button .button-text h2 {
            font-size: 50%;
        }
    }
</style>
