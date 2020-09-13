<template>
    <div class="default-right-container">
        <div class="inner-default-right">
            <div class="stupid-game">
                <div class="inner-stupid-game">
                    <iframe src="https://www.addictinggames.com/embed/html5-games/24553" scrolling="no"></iframe>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "defaultRight"
    }
</script>

<style scoped>
    @media screen {
        .default-right-container {
            width: 100%;
            height: 35em;
        }

        .inner-default-right {
            height: 90%;
            margin: 5%;
        }

        .stupid-game {
            width: 100%;
            height: 100%;
        }

        .inner-stupid-game {
            height: 100%;
            display: flex;
            flex-flow: column;
            justify-content: center;
            align-items: center;
        }

        iframe {
            height: 100%;
        }
    }
</style>
