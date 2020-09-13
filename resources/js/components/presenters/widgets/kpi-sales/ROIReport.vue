<template>
    <div class="kpi-piece">
        <div class="inner-kpi-piece" v-if="data !== ''">
            <div class="kpi-report">
                <div class="inner-kpi-report" style="text-align: center">
                    <!-- Daily Sales -->
                    <div style="width:100%;text-align: center;margin-top: 2em;">
                        <h2 style="text-align: center;">--- DAILY {{ getName(report) }} & ROI for {{ date }} ---</h2>

                        <div class="report-table">
                            <h2 style="text-align: center;">{{ data.name }}</h2>
                            <table style="margin:1em auto;">
                                <thead>
                                <tr>
                                    <th v-for="(columnName, cidx) in  data.columns" v-if="(columnName === 'Market') || (columnName === 'Memberships')">{{columnName}}</th>
                                    <th>{{ getName(report) + ' Spend' }}</th>
                                    <th>{{ getName(report) + ' 3Mo ROI' }}</th>
                                    <th>{{ getName(report) + ' 12Mo ROI' }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="(marketData, marketName) in  data.report">
                                    <td v-for="(mval, mcol) in marketData" v-if="(mcol === 'market') || (mcol === 'memberships')">{{ typeof(mval) !== 'number' ? mval : transformNumber(mval) }}</td>
                                    <td>{{ numSpend(data['roi'][getElem(report)]['spend'][marketName]) }}</td>
                                    <td>{{ numFormat(data['roi'][getElem(report)]['3mo'][marketName]) }}{{ data['roi'][getElem(report)]['3mo'][marketName] === '' ? '' : '%' }}</td>
                                    <td>{{ numFormat(data['roi'][getElem(report)]['12mo'][marketName]) }}{{ data['roi'][getElem(report)]['12mo'][marketName] === '' ? '' : '%' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="inner-kpi-piece" v-else>
            <div class="nothing-to-report">
                <div class="inner-nothing" style="text-align: center">
                    <p>Nothing to see here.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "WidgetScreen",
    props: ['report', 'data', 'date'],
    methods: {
        getElem(report) {
            if(report === 'facebook') {
                return 'fb'
            }
            else {
                return 'google'
            }
        },
        getName(report) {
           if(report === 'facebook') {
               return 'Facebook/IG.'
           }
           else {
               return 'Google'
           }
        },
        numSpend(num) {
            if(num !== '') {
                return parseFloat(num).toFixed(2);
            }
            else {
                return '';
            }
        },
        numFormat(num) {
            if(num !== '') {
                return parseFloat(num * 100).toFixed(2);
            }
            else {
                return '';
            }

        },
        transformNumber(n) {
            let results = 0;
            // Is int.
            if(Number(n) === n && n % 1 === 0)
            {
                results = parseInt(n);
            }
            else
            {
                results = parseFloat(n).toFixed(2);
            }
            return results;
        }
    },
}
</script>

<style scoped>
    @media screen {
        p {
            margin-top: 0.5em;
            font-weight: thin;
            text-transform: uppercase;
            letter-spacing: 0.1em
        }


    }

    @media screen and (max-width: 999px) {
        p {
            font-size: 0.75em;
        }

        h2, td, th {
            font-size: 60%;
        }
    }

    @media screen and (min-width: 1000px) {
        p {
            font-size: 1em;
        }

        h2, td, th {
            font-size: 80%;
        }
    }
</style>
