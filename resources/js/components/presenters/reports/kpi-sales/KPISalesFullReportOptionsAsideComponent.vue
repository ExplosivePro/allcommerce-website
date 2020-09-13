<template>
    <div class="kpi-section">
        <div class="inner-kpi-section">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label class="col-md-12 col-form-label" for="date-input" style="padding: 0">Report Date</label>
                    <div class="col-md-12 date-input">
                        <input class="form-control" id="date-input" type="date" name="date-input" placeholder="date" v-model="selectedReportDate"><span class="help-block">Please enter a valid date</span>
                    </div>
                </div>

                <div class="form-group-row">
                    <label class="col-md-12 col-form-label report-toggle-label">
                        <span>ROI Toggles</span>
                        <span><i :class="roiIcon" @click="showROIToggles = !showROIToggles" style="cursor: pointer"></i></span>
                    </label>

                    <div class="col-md-12 switch-wrap" v-for="(flag, toggle) in roiOptions" v-if="showROIToggles">
                        <div class="sw-label">
                            <label class="col-md-12 col-form-label toggle-label" style="padding: 0">{{ toggle }}</label>
                        </div>
                        <div class="sw-switch">
                            <label class="c-switch c-switch-label c-switch-pill c-switch-info">
                                <input class="c-switch-input" type="checkbox" v-model="roiOptions[toggle]" @change="toggleROI(toggle, roiOptions[toggle])"><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group-row">
                    <label class="col-md-12 col-form-label report-toggle-label">
                        <span>Report Toggles</span>
                        <span><i :class="reportIcon" @click="showReportToggles = !showReportToggles" style="cursor: pointer"></i></span>
                    </label>
                    <div class="col-md-12 switch-wrap" v-for="(report, slug) in reports" v-if="showReportToggles">
                        <div class="sw-label">
                            <label class="col-md-12 col-form-label toggle-label" style="padding: 0">{{ report.name }}</label>
                        </div>
                        <div class="sw-switch">
                            <label class="c-switch c-switch-label c-switch-pill c-switch-info">
                                <input class="c-switch-input" type="checkbox" :checked="report.show" @change="toggleReport(slug)"><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "KPISalesFullReportOptionsAsideComponent",
    props: ['reportDate', 'reports', 'newRoiOptions'],
    watch: {
        reports(report) {
            console.log('received new report -', report);
        },
        reportDate(date) {
            this.setReportDate(date);
        },
        selectedReportDate(date) {
            this.$emit('date-changed', date);
        },
        newRoiOptions(options) {
            console.log('Updating options', options)
            this.roiOptions = options;

            if(options.none) {
                this.toggleROI('none', options.none);
            }
            else if(options.all) {
                this.toggleROI('all', options.all);
            }
            else if(options.fb) {
                this.toggleROI('fb', options.fb);
            }
            else if(options.google) {
                this.toggleROI('google', options.google);
            }
        }
    },
    data() {
        return {
            showReportToggles: false,
            showROIToggles: false,
            selectedReportDate: '',
            roiOptions: {
                'none': true,
                'all': false,
                'fb': false,
                'google': false,
            },
        };
    },
    computed: {
        reportIcon() {
            return this.showReportToggles ? 'fad fa-minus-square' : 'fad fa-plus-square'
        },
        roiIcon() {
            return this.showROIToggles ? 'fad fa-minus-square' : 'fad fa-plus-square'
        }
    },
    methods: {
        setReportDate(date) {
            this.selectedReportDate = date;
        },
        toggleReport(slug) {
            (this.reports[slug]['show'])
                ? console.log('Will shut off '+this.reports[slug].name)
                : console.log('Will turn on '+this.reports[slug].name);

            this.$emit('toggle-report', slug)

        },
        toggleROI(toggle, flag) {
            console.log(`Toggling roiOption ${toggle} to ${flag}`);

            switch(toggle) {
                case 'all':
                    if(flag) {
                        this.roiOptions['none'] = false;
                        this.roiOptions['fb'] = true;
                        this.roiOptions['google'] = true;
                    }
                    else {
                        this.toggleROI('none', true);
                    }
                    break;

                case 'fb':
                    if(flag) {
                        this.roiOptions['none'] = false;

                        if((this.roiOptions['fb']) && (this.roiOptions['google']))
                        {
                            let _this = this;
                            setTimeout(function () { _this.roiOptions['all'] = true; }, 100)
                        }
                    }
                    else {
                        this.roiOptions['all'] = false;
                        this.toggleROI('none', false);
                    }
                    break;

                case 'google':
                    if(flag) {
                        this.roiOptions['none'] = false;

                        if((this.roiOptions['fb']) && (this.roiOptions['google']))
                        {
                            let _this = this;
                            setTimeout(function () { _this.roiOptions['all'] = true; }, 100)

                        }
                    }
                    else {
                        this.roiOptions['all'] = false;
                        this.toggleROI('none', false);
                    }
                    break;

                default:
                    if(flag === true) {
                        this.roiOptions = {
                            'none': true,
                            'all': false,
                            'fb': false,
                            'google': false,
                        }
                    }
                    else {
                        if((!this.roiOptions['all']) && (!this.roiOptions['fb']) && (!this.roiOptions['google']))
                        {
                            console.log('Setting none to true cuz everything else is empty');
                            let _this = this;
                            setTimeout(function () { _this.roiOptions['none'] = true; _this.$emit('toggle-roi', _this.roiOptions); }, 100)
                        }
                    }
            }
            this.$emit('toggle-roi', this.roiOptions);
        }
    },
    mounted() {
        this.setReportDate(this.reportDate);

        if(this.newRoiOptions !== '') {
            console.log('Mounted! toggling options', this.newRoiOptions);
            this.roiOptions = this.newRoiOptions;
            let options = this.roiOptions;

            if(options.none) {
                this.toggleROI('none', options.none);
            }
            else if(options.all) {
                this.toggleROI('all', options.all);
            }
            else if(options.fb) {
                this.toggleROI('fb', options.fb);
            }
            else if(options.google) {
                this.toggleROI('google', options.google);
            }
        }
    }
}
</script>

<style scoped>
    @media screen {
        .kpi-section {
            width: 100%;
            height: 100%;
        }

        .inner-kpi-section {
            display: flex;
            flex-flow: column;
        }

        .form-horizontal {
            width: 100%;
            height: 100%;

            display: flex;
            flex-flow: column;
        }

        .form-group.row {
            margin: 0;
        }

        .report-toggle-label {
            display: flex;
            flex-flow: row;
            justify-content: space-between;
            padding: 1.5em 0 0.5em;
        }

        .form-group.row .col-form-label {
            padding: 0;
        }

        .date-input {
            padding: 0;
        }

        .date-input #date-input{
            width: 91%;
        }

        .switch-wrap {
            display: flex;
            flex-flow: row;
            padding: 0;
        }

        .sw-label {
            width: 77.5%;
        }
    }

    @media screen and (max-width: 999px) {
        .toggle-label {
            font-size: 75%;
        }
    }

    @media screen and (min-width: 1000px) {
        .toggle-label {
            font-size: 80%;
        }
    }
</style>
