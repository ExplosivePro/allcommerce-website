<template>
    <div class="all-the-stuff-segment">
        <div class="inner-all-the-stuff">
            <div class="stuff-control-panel">
                <h1> Control Stuff Here!!!!</h1>
            </div>
            <div class="all-off-the-actual-stuff-container">
                <div class="inner-actual-stuff">
                    <div class="actual-stuff">
                        <h1> No Stuff Here! d:</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InventoryGridComponent"
    }
</script>

<style scoped>
    /* Styles */
    @media screen {

    }

    /* Styles */
    @media screen and (max-width: 767px) {
        @media screen {

        }

        @media screen and (max-width: 999px) {

        }

        @media screen and (min-width: 1000px) {

        }
    }

    /* Styles */
    @media screen and (min-width: 768px) and (max-width: 999px) {

    }

    /* Styles */
    @media screen and (min-width: 1000px) {
        @media screen {
            .all-the-stuff-segment {
                width: 100%;
            }

            .all-the-stuff-segment .inner-all-the-stuff {
                display: flex;
                flex-flow: column;
            }

            .all-the-stuff-segment .inner-all-the-stuff .stuff-control-panel {
                border-bottom: 1px solid #000;
                width: 100%;
            }


        }

        @media screen and (max-width: 1440px) {

        }

        @media screen and (min-width: 1441px) {

        }
    }
</style>
