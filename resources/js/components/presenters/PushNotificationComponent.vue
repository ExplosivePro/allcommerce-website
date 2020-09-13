<template>
    <div class="push-notes-container">
        <div class="inner-push-container">
            <div class="no-apps-section" v-if="apps.length === 0">
                <div class="inner-no-apps" v-if="apps.length === 0">
                    <h1>There Are No Available Apps for {{ client }}!</h1>
                    <small v-if="isHost === true">Please select another client from the sidebar.</small>
                    <small v-else>Please contact your account manager if this is a mistake.</small>
                </div>
            </div>

            <div class="apps-section" v-else>
                <div :class="innerAppsClass">
                    <div class="top-row">
                        <div class="inner-top-row">
                            <label class="select-label">Available Mobile Apps</label>
                            <multiselect v-model="appInput"
                                         :options="sortAppsForSelect"
                                         :custom-label="dropAppLabel"
                                         :placeholder="appPlaceholder"
                                         label="app"
                                         track-by="app"
                            ></multiselect>
                        </div>
                    </div>

                    <div class="bottom-row" v-if="firingMessage">
                        <div :class="innerBottomRowClass" :style="'justify-content: center;'">
                            <div class="filter-segment">
                                <div class="inner-filter-segment">
                                    <label class="select-label">Doing it.</label>
                                    <div class="spinny-loader" v-if="firingMessage">
                                    <div class="center-wrapper">
                                        <i class="fad fa-galaxy fa-spin"></i>
                                        <p>Sending Message...</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bottom-row" v-else-if="selectedApp !== ''">
                        <div :class="innerBottomRowClass">
                            <label class="select-label c-panel">Control Panel</label>
                            <div class="filter-segment">
                                <div class="inner-filter-segment">
                                    <div class="comm-spot spot">
                                        <div class="inner-comm-spot">
                                            <div class="msg-area">
                                                <div class="inner-msg-area">
                                                    <label for="textMsg" class="select-label">Msg To Send</label>
                                                    <textarea class="text-msg" id="textMsg" name="msg" rows="5" v-model="textMsg" :disabled="!formReady"></textarea>
                                                </div>
                                            </div>

                                            <div class="add-url-area">
                                                <div class="inner-add-url">
                                                    <div class="add-url-check-box-bit">
                                                        <div class="inner-box-bit">
                                                            <input type="checkbox" value="true" v-model="includeUrl" :disabled="!formReady"/><label class="select-label">Open URL on Msg Open?</label>
                                                        </div>
                                                    </div>
                                                    <div class="add-url-text-input-bit" v-if="includeUrl">
                                                        <div class="inner-box-bit">
                                                            <label class="select-label">URL</label>
                                                            <input type="text" v-model="textUrl" placeholder="https://www.google.com"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="filter-spot spot">
                                        <div class="inner-filter-spot">
                                            <label class="select-label">Filters</label>
                                            <div class="spinny-loader" v-if="loadingFilters">
                                                <div class="center-wrapper">
                                                    <i class="fad fa-galaxy fa-spin"></i>
                                                    <p>Loading Filters...</p>
                                                </div>
                                            </div>
                                            <div class="loaded-content" v-else>
                                                <div class="inner-loaded-content">
                                                    <div class="spinny-loader" v-if="filters === ''">
                                                        <div class="center-wrapper">
                                                            <i class="fad fa-bomb fa-rotate-270 faa-pulse animated faa-fast"></i>
                                                            <p>Could Not Contact Client Server.</p>
                                                        </div>
                                                    </div>
                                                    <div class="spinny-loader" v-else-if="(filterType(filters) !== 'string') && (filters.length === 0)">
                                                        <div class="center-wrapper">
                                                            <i class="fad fa-bug faa-ring animated faa-slow"></i>
                                                            <p>No Available Filters.</p>
                                                        </div>
                                                    </div>
                                                    <div class="spinny-loader" v-else-if="filterType(filters) === 'string'">
                                                        <div class="center-wrapper">
                                                            <i class="fad fa-bug faa-ring animated faa-slow"></i>
                                                            <p>{{ filters }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="yay-success" v-else-if="(filterType(filters) !== 'string') && (filters.length > 0)">
                                                        <div class="inner-actual-filers">
                                                            <div class="filter-row" v-for="(filter, idx) in filters">
                                                                <div class="inner-filter-row" v-if="filterExpression(filter.vals) === 'object'">
                                                                    <multiselect v-model="selectedFilters[idx]"
                                                                                 :options="filter['vals']"
                                                                                 :custom-label="function (fil){ return  fil['val']}"
                                                                                 :placeholder="'Select ' + filter['name']"
                                                                                 @input="(val) => updateSelectedFilterAndDoSomethingAboutIt(filter['name'], val)"
                                                                                 label="val"
                                                                                 track-by="col"
                                                                    ></multiselect>
                                                                </div>
                                                            </div>

                                                            <div class="filter-row">
                                                                <div class="inner-box-bit">
                                                                    <input type="checkbox" value="true" v-model="showAvailablePeeps" :disabled="amtUsers === 0"/><label class="select-label">Show Available Users</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="button-spot spot">
                                        <div class="inner-button-spot">
                                            <button type="button" class="btn btn-info" @click="submitPanel" :disabled="amtUsers === 0">Fire Notification to {{ getAmtUsers }} {{ getAmtUsers === 1 ? 'User' : 'Users' }}</button>
                                            <button type="button" class="btn btn-danger" @click="clearPanel">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="people-segment" v-if="showAvailablePeeps">
                                <div class="inner-people-segment">
                                    <v-card>
                                        <v-card-title>
                                            Available Users
                                            <div class="flex-grow-1"></div>
                                            <v-text-field
                                                v-model="search"
                                                append-icon="fal fa-search"
                                                label="Search"
                                                single-line
                                                hide-details
                                            ></v-text-field>
                                        </v-card-title>
                                        <v-data-table
                                            v-model="selectedUsers"
                                            :headers="tableHeaders"
                                            :items="users"
                                            :search="search"
                                            show-select
                                            item-key="uuid"
                                            :items-per-page="itemsPerPage"
                                            class="elevation-2"
                                            :footer-props="footerProps"
                                        ></v-data-table>
                                    </v-card>
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
    import Multiselect from 'vue-multiselect';
    import "vuetify/dist/vuetify.min.css";

    const opts = {
        icons: {
            iconfont: 'fa', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
        },
    };

    export default {
        name: "PushNotificationComponent",
        vuetify : new Vuetify(opts),
        components: { Multiselect },
        props: ['apps', 'client', 'isHost', 'loadingFilters', 'filters', 'amtUsers', 'users', 'firingMessage'],
        watch: {
            appInput(app) {
                this.selectedApp = app.app;
                console.log('appInput - selected App changed! ', app.label);

                if(this.selectedApp !== '') {
                    this.$emit('ping-for-filters', this.selectedApp)
                }
                else {
                    this.$emit('clear-loading');
                }
            },
            filters(data) {
                if(data.length > 0) {
                    this.selectedFilters = {};
                    for(let f in data) {
                        this.selectedFilters[f] = '';
                    }
                    this.formReady = true;
                }
            },
            selectedUsers(data) {
                console.log('Updating selectedUsers - ', data);
            }
        },
        data() {
            return {
                appInput: '',
                selectedApp: '',
                showAvailablePeeps: false,
                textMsg: '',
                textUrl: '',
                includeUrl: false,
                formReady: false,
                selectedFilters: {},
                search: '',
                selectedUsers: [],
                tableHeaders: [
                    //{ text: 'Member ID', align: 'left', value: 'name', sortable: true},
                    { text: 'First Name', sortable: true, value: 'first_name'},
                    { text: 'Last Name',  sortable: true, value: 'last_name' },
                    { text: 'Email', value: 'email' },
                    //{ text: 'Home Club', value: 'home_club_id' },
                    //{ text: 'Last Login', value: 'last_login'}
                ],
                itemsPerPage: 200,
                footerProps: {
                    'items-per-page-options': [10,100,200,500,1000,-1]
                },
                pagination: {
                    rowsPerPage: 200
                },
            }
        },
        computed: {
            innerAppsClass() {
                let className = 'inner-apps no-bottom';

                if(this.selectedApp !== '') {
                    className = 'inner-apps with-bottom';
                }

                return className;
            },
            innerBottomRowClass() {
                let className = 'inner-bottom-row no-people';

                if(this.showAvailablePeeps) {
                    className = 'inner-bottom-row with-people';
                }

                return className;
            },
            sortAppsForSelect() {
                let apps = [];
                if(this.apps.length > 0) {
                    apps = [{app: '', label: 'Select a Mobile App!'}];
                    let unique = [...new Set(this.apps.map(item => item.id))];

                    this.apps.forEach( a => {
                        let index = unique.indexOf(a.id);
                        if(index !== -1){
                            apps.push({app: a.id, label : a.name});
                            unique.splice(index,1);
                        }
                    });
                }

                return apps;
            },
            appPlaceholder() {
                let input = 'No Mobile Apps Available!';

                if(this.sortAppsForSelect.length > 0) {
                    input = 'Select a Mobile App!'
                }
                else if(this.loading) {
                    input = 'Loading...!';
                }

                return input;
            },
            getAmtUsers() {
                let amt = this.amtUsers;

                if(this.showAvailablePeeps) {
                    amt = this.selectedUsers.length;
                }

                return amt;
            },
        },
        methods: {
            dropAppLabel({label}) {
                return `${label}`
            },
            dropFilterLabel({val}) {
                return `${val}`
            },
            filterType(obj) {
                return typeof obj;
            },
            filterExpression(obj) {
                let res = 'string';

                if(typeof obj !== 'string') {
                    res = 'object'
                }

                return res
            },
            updateSelectedFilterAndDoSomethingAboutIt(input, val) {
                let sf = [];
                if(val.val === 'Select a filter option') {
                    console.log('Resetting filters');
                    for(let x in this.filters) {
                        if(this.filters[x].name === input) {
                           sf[x] = '';
                        }
                    }
                }
                else {

                    for(let x in this.filters) {
                        if(this.filters[x].name === input) {
                            sf[x] = val.col;
                        }
                    }
                }
                console.log('Filter was updated - '+input, sf);
                this.$emit('ping-users', sf);
            },
            clearPanel() {
                for(let x in this.selectedFilters) {
                    this.selectedFilters[x] = '';
                }
                this.textMsg = '';
                this.textUrl = '';
                this.includeUrl = false;
                this.search = '';
                this.selectedUser = [];
                this.showAvailablePeeps = false;
            },
            submitPanel() {
                if(this.getAmtUsers > 0) {
                    if(this.textMsg !== '') {
                        let entity =  (this.showAvailablePeeps) ? this.selectedUsers : this.users;
                        let users = [];
                        for(let x in entity) {
                            users.push(entity[x].expo_push_token)
                        }
                        let payload = {
                            users: users,
                            msg: this.textMsg
                        };

                        if(this.textUrl !== '') {
                            payload['url'] = this.textUrl
                        }

                        this.$emit('fire', payload);
                    }
                    else
                    {
                        alert('Msg to Send cannot be empty');
                    }
                }
                else {
                    alert('Need at least one user to send message to');
                }
            }
        },
        mounted() {
            console.log('Push Notes Screen mounted!');
            console.log('Push Notes Screen - checking passed in apps! ',this.apps);
        }
    }
</script>


<style scoped>
    @media screen {
        .push-notes-container {
            width: 100%;
            height: 100%;
            margin: 0
        }

        .inner-push-container {
            display: flex;
            flex-flow: column;
            height: 100%;
        }

        .no-apps-section, .apps-section {
            width: 100%;
            height: 100%;
        }

        .inner-no-apps {
            display: flex;
            flex-flow: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
        }

        .inner-apps {
            display: flex;
            flex-flow: column;
            height: 100%;
        }

        .inner-apps.no-bottom {
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .inner-apps.with-bottom {
            text-align: center;
        }

        .inner-apps.with-bottom .top-row {
            height: 20%;
        }

        .inner-apps .top-row {
            width: 100%;
        }

        .select-label {
            text-align: left;
            width: 100%;
        }

        .bottom-row {
            width: 100%;
            height: 80%;
        }

        .inner-bottom-row {
            display:flex;
            height: 100%;
        }

        .spinny-loader {
            text-align: center;
            height: 100%;
        }

        .spinny-loader .center-wrapper {
            height: 100%;
            justify-content: center;
            display: flex;
            flex-flow: column;
        }

        .spinny-loader i {
            font-size: 3em;
        }

        .spinny-loader p {
            margin-top: 0.5em;
            font-weight: thin;
            font-size: 1.25em;
            text-transform: uppercase;
            letter-spacing: 0.1em
        }

        .inner-bottom-row .inner-filter-spot .loaded-content .filter-row {
            margin-top: 2.5%;
        }

        textarea {
            background-color: #fff !important;
            border-radius: 0.25em;
        }
    }

    @media screen and (max-width: 999px) {
        .inner-apps .inner-top-row {
            margin: 0 5%;
        }

        .inner-bottom-row {
            flex-flow: column;
        }

        .inner-bottom-row .inner-filter-segment {
            background-color: #e9ecef;
            border-radius: 0.5em;
            padding: 2% 0;
            display: flex;
            flex-flow: row wrap;
            height: 100%;
        }

        .c-dark-theme .inner-bottom-row .inner-filter-segment {
            background-color: #3c4b64;
        }

        .inner-bottom-row .inner-filter-segment .spot {
            width: 100%;
        }

        .spot .inner-comm-spot,
        .spot .inner-filter-spot,
        .spot .inner-button-spot
        {
            margin: 0 5%;
        }

        .spot .inner-comm-spot textarea {
            width: 100%;
        }

        .add-url-check-box-bit .inner-box-bit, .filter-row .inner-box-bit {
            display: flex;
            flex-flow: row;
        }

        .add-url-check-box-bit .inner-box-bit input, .filter-row .inner-box-bit input {
            width: 5%;
            margin-top: 0.25rem;
            margin-right: 1em;
        }

        .add-url-check-box-bit .inner-box-bit label, .filter-row .inner-box-bit label {
            width: 95%;
        }

        .inner-add-url .inner-box-bit {
            display: flex;
            flex-flow: row;
            margin-bottom: 5%;
        }

        .add-url-text-input-bit .inner-box-bit label {
            width: 10%;
        }

        .add-url-text-input-bit .inner-box-bit input {
            width: 90%;
        }

        .inner-button-spot {
            margin-top: 5% !important;
        }

        .inner-bottom-row.with-people .people-segment {
            width: 100%;
            overflow-x: scroll;
            max-height: 40em;
        }

        .spinny-loader {
            width: 100%;
        }
    }

    @media screen and (min-width: 1000px) {
        .inner-apps .inner-top-row {
            margin: 0 15%;
        }

        .inner-bottom-row.no-people {
            flex-flow: column;
        }

        .inner-bottom-row.no-people .c-panel {
            margin: 0 5%;
        }

        .inner-bottom-row.no-people .filter-segment {
            width: 100%;
        }

        .inner-bottom-row.no-people .inner-filter-segment {
            margin: 0 5%;
            background-color: #e9ecef;
            border-radius: 0.5em;
            padding: 2% 0;
            display: flex;
            flex-flow: row wrap;
            height: 100%;
        }

        .c-dark-theme .inner-bottom-row .inner-filter-segment,
        .c-dark-theme .inner-bottom-row.with-people .inner-filter-segment {
            background-color: #3c4b64;
        }

        .inner-bottom-row.no-people .comm-spot {
            width: 50%;
        }

        .inner-bottom-row.no-people .filter-spot {
            width: 50%;
        }

        .inner-bottom-row.no-people .button-spot {
            width: 100%;
        }

        .inner-bottom-row.no-people .msg-area {
            width: 100%;
            text-align: left;
        }

        .inner-bottom-row.no-people .inner-msg-area {
            margin-left: 2.5%;
        }

        .inner-msg-area textarea {
            width: 100%;
        }

        .inner-bottom-row.no-people .add-url-area {
            width: 100%;
            text-align: left;
        }

        .inner-bottom-row.no-people .inner-add-url {
            margin-left: 2.5%;
        }

        .inner-bottom-row .inner-filter-spot {
            height: 100%;
            justify-content: center;
            display: flex;
            flex-flow: column;
            margin: 0 2.5%;
        }

        .inner-bottom-row .inner-filter-spot .select-label {
            margin-left: 2.5%;
            height: 15%;
        }

        .inner-bottom-row .inner-filter-spot .loaded-content {
            height: 85%;
        }

        .inner-add-url .inner-box-bit, .filter-row .inner-box-bit {
            display: flex;
            flex-flow: row;
        }

        .inner-add-url .add-url-check-box-bit .inner-box-bit input, .filter-row .inner-box-bit input {
            margin-top: 0.25rem;
            margin-right: 1em;
        }

        .inner-add-url .add-url-text-input-bit .inner-box-bit label {
            width: 10%;
        }

        .inner-add-url .add-url-text-input-bit .inner-box-bit input {
            width: 90%;
        }


        .inner-bottom-row.with-people {
            flex-flow: row wrap;
        }

        .inner-bottom-row.with-people .filter-segment {
            width: 50%;
        }

        .inner-bottom-row.with-people .inner-filter-segment {
            background-color: #e9ecef;
            border-radius: 0.5em;
            padding: 2% 5%;
            display: flex;
            flex-flow: column;
            height: 100%;
        }

        .inner-bottom-row.with-people .people-segment {
            width: 50%;
            overflow-x: scroll;
            max-height: 40em;
        }
    }
</style>
