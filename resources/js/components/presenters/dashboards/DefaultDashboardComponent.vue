<template>
    <div class="dash-container card">
        <header class="card-header">{{ title }}</header>
        <div class="inner-dash-container">
            <div class="dash-content">
                <div class="inner-dash-content">
                    <div class="loading-piece" v-if="loading">
                        <div class="inner-loading-piece">
                            <sexy-hurricane-loading
                                loading-msg="Loading Widgets..."
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
                            <div class="top-dashboard-section">
                                <div class="inner-top-dashboard"></div>
                            </div>

                            <div class="lower-dashboard-section">
                                <div class="inner-lower-dashboard">
                                    <div class="left-dashboard-section">
                                        <div class="inner-left-dashboard">
                                            <div class="widget-box" v-for="(widgetData, idx) in leftWidgets">
                                                <div class="inner-widget">
                                                    <div class="widget-title-segment card">
                                                        <header class="card-header">
                                                            <div>{{ widgetData.name }}</div>
                                                            <small>{{ widgetData.description }}</small>
                                                        </header>

                                                        <component v-bind:is="widgetData['component_name']"
                                                                   :client-id="widgetData['client_id']"
                                                        ></component>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right-dashboard-section">
                                        <div class="inner-right-dashboard">
                                            <div class="widget-box" v-for="(widgetData, idx) in rightWidgets">
                                                <div class="inner-widget">
                                                    <div class="widget-title-segment card">
                                                        <header class="card-header">
                                                            <div>{{ widgetData.name }}</div>
                                                            <small>{{ widgetData.description }}</small>
                                                        </header>

                                                        <component v-bind:is="widgetData['component_name']"
                                                                   :client-id="widgetData['client_id']"
                                                        ></component>
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
            </div>
        </div>
    </div>
</template>

<script>
    import SexyHurricaneLoading from '../widgets/loading/SexyHurricane';

    export default {
        name: "DefaultDashboardComponent",
        components: {
            SexyHurricaneLoading
        },
        props: ['title', 'error', 'loading', 'topWidgets', 'leftWidgets', 'rightWidgets']
    }
</script>

<style scoped>
    @media screen {
        .dash-container {
            background-color: #e9ecef;
            border-radius: 0.25rem;
        }

        .c-dark-theme .dash-container {
            background-color: #2c2c34;
        }

        .inner-dash-container {
            padding: 0 2.5% 2.5%;

            display: flex;
            flex-flow: column;
        }

        .inner-dash-title {
            text-align: center;
            height: 10%;
            width: 100%;
        }

        .dash-content {
            height: 100%;
            width: 100%;
        }

        .inner-dash-content {
            height: 100%;
            margin-top: 1%;

            display: flex;
            flex-flow: column;
            justify-content: center;
            align-items: center;
        }

        .dash-content .content-piece {
            width: 100%;
            height: 100%;
        }

        .inner-content-piece {
            display: flex;
            flex-flow:column;
        }

        .top-dashboard-section {
            width: 100%;
        }

        .inner-widget-title {
            text-align: center;
        }

        .inner-widget-title h3 {
            margin: 0;
        }

        .lower-dashboard-section {
            width: 100%;
        }
    }

    @media screen and (max-width: 999px) {
        .inner-lower-dashboard {
            display: flex;
            flex-flow:column;
        }

        .left-dashboard-section,
        .right-dashboard-section {
            width: 100%;
        }
    }

    @media screen and (min-width: 1000px) {
        .inner-lower-dashboard {
            display: flex;
            flex-flow: row;
        }

        .left-dashboard-section,
        .right-dashboard-section {
            width: 50%;
        }

        .inner-left-dashboard,
        .inner-right-dashboard {
            margin-top: 3%;

            display: flex;
            flex-flow: column;
        }

        .inner-left-dashboard {
            margin-right: 3%;
        }
    }
</style>
