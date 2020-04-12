<style>
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
            .inner-card {
                width: 100%;
                height: 100%;

                display: flex;
                flex-flow: column;
            }

            .control-panel {
                width: 100%;
                height: 100%;
            }

            .inner-control-panel {
                margin-top: 1em;

                display: grid;
                grid-template-columns: 30% 70%;
                grid-template-rows: 2.5em auto;
            }

            .page-title-segment {
                grid-column: 1;
                grid-row: 1;
            }

            .action-panel-segment {
                grid-column: 1;
                grid-row: 2;
            }

            .inner-action-panel {
                height: 100%;
                width: 100%;
                display: flex;
                flex-flow: row;
            }

            .action-button {
                width: 33%;
                height: 100%;
            }

            .inner-action-button {
                height: 100%;
                width: 100%;
            }

            .inner-action-button button {
                height: 100%;
                width: 100%;
            }

            .button-panel-segment {
                grid-column: 2;
                grid-row-start: 1;
                grid-row-end: 3;

                height: 100%;
                width: 100%;
            }

            .inner-button-panel {
                height: 100%;
                width: 100%;
                display: flex;
                flex-flow: row;
                align-items: center;
                justify-content: flex-end;
            }

            .page-title {
                margin: 0;
            }

            .content-grid {
                width: 100%;
            }


        }

        @media screen and (max-width: 1440px) {

        }

        @media screen and (min-width: 1441px) {

        }
    }
</style>
