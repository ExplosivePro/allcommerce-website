<template>
    <default-dashboard
        :title="dashboardTitle"
        :error="errorMsg"
        :loading="loading"
        :top-widgets="topWidgets"
        :left-widgets="leftWidgets"
        :right-widgets="rightWidgets"
    ></default-dashboard>
</template>

<script>
    import DefaultDashboard from '../../presenters/dashboards/DefaultDashboardComponent';
    import { mapGetters,  mapActions } from 'vuex'

    export default {
        name: "DefaultDashboardContainer",
        components: {
            DefaultDashboard
        },
        props: ['clientName'],
        watch: {
            widgets(gets) {
                this.processWidgets(gets);
                this.loading = false;
            },
            widgetsErrorMsg(msg) {
                console.log('Received widget error - '+ msg);
                if(msg === '') {
                    this.errorMsg = '';
                    this.loadDefaultWidgets()
                }
                else {
                    if(msg === 'No Widgets Assigned') {
                        this.errorMsg = '';
                        this.loadDefaultWidgets();
                    }
                    else
                    {
                        this.errorMsg = msg;
                    }

                }
                this.loading = false;
            },
            rightWidgetSwap(id) {
                for(let x = 0; x < this.availableRightWidgets.length; x++) {
                    console.log(this.availableRightWidgets[x]);
                    if(this.availableRightWidgets[x].id === id) {
                        this.rightWidgets = [];
                        this.rightWidgets.push(this.availableRightWidgets[x]);
                        break;
                    }
                }
            }
        },
        data() {
            return {
                error: false,
                errorMsg: '',
                loading: false,
                topWidgets: [],
                leftWidgets: [],
                rightWidgets: [],
                availableLeftWidgets: [],
                availableRightWidgets: [],
                availableTopWidgets: [],
            };
        },
        computed: {
            ...mapGetters({
                widgets: 'dashboard/widgets',
                widgetsErrorMsg: 'dashboard/widgetsErrorMsg',
                rightWidgetSwap: 'dashboard/rightWidgetSwap',
            }),
            dashboardTitle() {
                return `Reporting for ${this.clientName}`;
            },
        },
        methods: {
            getAvailableWidgets() {
                this.loading = true;
                this.getUserAvailableWidgets();
            },
            processWidgets(widgets) {
                console.log('Processing widgets - ', widgets);
                this.availableWidgets = widgets['available'];

                for(let x in widgets['available']) {
                    switch(widgets['available'][x]['location']) {
                        case 'left':
                            this.availableLeftWidgets.push(widgets['available'][x]);
                            break;

                        case 'right':
                            this.availableRightWidgets.push(widgets['available'][x]);
                            break;

                        case 'center':
                        default:
                            this.availableTopWidgets.push(widgets['available'][x])
                    }
                }

                console.log('available left widgets - ', this.availableLeftWidgets);
                console.log('available right widgets - ', this.availableRightWidgets);
                console.log('available top widgets - ', this.availableTopWidgets);

                for(let x in widgets['default']) {
                    switch(widgets['default'][x]['location']) {
                        case 'left':
                            this.leftWidgets.push(widgets['default'][x])
                            break;

                        case 'right':
                            this.rightWidgets.push(widgets['default'][x]);
                            break;

                        case 'center':
                        default:
                            this.topWidgets.push(widgets['default'][x])
                    }
                }

                console.log('default left widgets - ', this.leftWidgets);
                console.log('default right widgets - ', this.rightWidgets);
                console.log('default top widgets - ', this.topWidgets);
                //this.processedWidgets = widgets['default'];

                // prefill the top left and right with any available widgets (if available)
                if((this.leftWidgets.length === 0) && (this.availableLeftWidgets.length !== 0))
                {
                    this.leftWidgets.push(this.availableLeftWidgets[0]);
                }

                if((this.rightWidgets.length === 0) && (this.availableRightWidgets.length !== 0))
                {
                    this.rightWidgets.push(this.availableRightWidgets[0]);
                }

                if((this.topWidgets.length === 0) && (this.availableTopWidgets.length !== 0))
                {
                    this.topWidgets.push(this.availableTopWidgets[0]);
                }
                // if the default widgets are empty;

                this.loadDefaultWidgets();

            },
            loadDefaultWidgets() {
                if((this.leftWidgets.length === 0) && (this.availableLeftWidgets.length === 0)) {
                    let left = [
                        {
                            name: 'Announcement',
                            description: '',
                            'component_name': 'default-left-widget',
                            'client_id': 1

                        }
                    ];

                    this.availableLeftWidgets = left;
                    this.leftWidgets = left;
                }

                if((this.rightWidgets.length === 0) && (this.availableRightWidgets.length === 0)) {
                    let right = [
                        {
                            name: 'Play a Dumb Game',
                            description: 'While you wait...',
                            'component_name': 'default-right-widget',
                            'client_id': 1
                        }
                    ];

                    this.availableRightWidgets = right;
                    this.rightWidgets = right;
                }

                // @todo - the top widgets!
                let top = [];

                this.errorMsg = '';
                this.loading = false;
            },
            ...mapActions({
                getUserAvailableWidgets: 'dashboard/getUserAvailableWidgets'
            }),
        },
        mounted() {
            this.getAvailableWidgets();
            console.log('Default Dashboard loaded!');
        }
    }
</script>

<style scoped>

</style>
