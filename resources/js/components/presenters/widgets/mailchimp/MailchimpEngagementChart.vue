<template>
    <div class="mailchimp-widget-container">
        <div class="inner-mailchimp-widget">
            <div class="reporting-zone">
                <div class="inner-reporting-zone">
                    <div class="loading-piece" v-if="loading">
                        <div class="inner-loading-piece">
                            <sexy-hurricane-loading
                                loading-msg="Generating Mailchimp Report..."
                            ></sexy-hurricane-loading>
                        </div>
                    </div>
                    <div class="error-piece" v-else-if="(!loading) && (error !== '')">
                        <div class="inner-error-piece">
                            <p>{{ error }}</p>
                        </div>
                    </div>
                    <div class="mailchimp-piece" v-show="(!loading) && (error === '')">
                        <div class="inner-mailchimp-piece">
                            <div style="width:100%;">
                                <canvas id="canvas" height="500" width="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js';
import SexyHurricaneLoading from '../loading/SexyHurricane';

let chart = Chart;
export default {
    name: "MailchimpEngagementChart",
    components: {
        SexyHurricaneLoading
    },
    props: ['loading', 'error', 'reports'],
    watch: {
        reports(data) {
            console.log('New Reports logged - ', data);
            this.renderChart(data);
        }
    },
    methods: {
        renderChart(data) {
            let ctx = document.getElementById('canvas').getContext('2d');
            window.myLine = new chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Campaign Engagement (Last 7 Days)'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                        }],
                        yAxes: [{
                            display: true,
                        }]
                    }
                }
            });

            window.myLine.update();
        }
    },
}
</script>

<style scoped>
@media screen {
    .mailchimp-widget-container {
        background-color: lightgray;
        border-radius: 0.2em;
    }

    .c-dark-theme .mailchimp-widget-container {
        background-color: #1e1e29;
    }

    .inner-mailchimp-widget {
        padding: 2%;
    }

    .reporting-zone {
        width: 100%;
        height: 100%;
    }

    .inner-reporting-zone
    {
        background-color: #fff;
        border-radius: 0.2em;
        position:relative;
        -webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        -moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
    }
    .inner-reporting-zone:before, .inner-reporting-zone:after
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
    .inner-reporting-zone:after
    {
        right:10px;
        left:auto;
        -webkit-transform:skew(8deg) rotate(3deg);
        -moz-transform:skew(8deg) rotate(3deg);
        -ms-transform:skew(8deg) rotate(3deg);
        -o-transform:skew(8deg) rotate(3deg);
        transform:skew(8deg) rotate(3deg);
    }

    .c-dark-theme .inner-reporting-zone {
        background-color: #2c2c34;
    }
}

@media screen and (max-width: 999px) {
    h2, td, th {
        font-size: 60%;
    }
}

@media screen and (min-width: 1000px) {
    h2, td, th {
        font-size: 80%;
    }
}
</style>
