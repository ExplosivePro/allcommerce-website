<template>
    <div class="sms-merchant-manager">
        <div class="inner-sms-merchant-manager">
            <div class="list-description">
                <div class="inner-list-description">
                    <h1>Select a Shop to Manage their Payment Gateways</h1>
                </div>
            </div>

            <div class="list-of-shops">
                <div class="inner-list-of-shops">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col" v-for="(field, x) in shopTableFields">{{ field }}</th>
                            <th>More</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(provider, idx) in shops">
                            <td v-for="(val, col) in provider" :scope="(col === 'title') ? 'row' : ''" v-if="(col === 'name')">{{ val }}</td>
                            <td>{{ provider.shoptype.name }}</td>
                            <td>{{ renderShopTableCCField(provider['curated_payment_gateways']['credit']) }}</td>
                            <td>{{ renderShopTableExField(provider['curated_payment_gateways']['express']) }}</td>
                            <td>{{ renderShopTableInField(provider['curated_payment_gateways']['install']) }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" @click="triggerShopGatewaySetup(idx)" :disabled="provider.disabled"> Manage
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="cards-o-payment-gateways">
                <div class="inner-card-list">
                    <div class="loading-section" v-if="loading">
                        <div class="inner-loading-section">
                            <sexy-hurricane :loading-msg="loadingMsg"></sexy-hurricane>
                        </div>
                    </div>
                    <div class="card-section" v-if="showShopGateways">
                        <div class="inner-card-section">
                            <div class="go-back-btn">
                                <div class="inner-go-back-btn">
                                    <button type="button" class="btn btn-link" @click="reToggleMerchantPage()"><span><i class="fas fa-arrow-alt-left"></i></span> Go Back</button>
                                </div>
                            </div>
                            <div class="card-announce">
                                <div class="inner-card-announce">
                                    <h1>Manage Gateways for {{ shops[activeShop].name }}</h1>
                                </div>
                            </div>

                            <div class="card-type-row" v-for="(typeData, idx) in gatewayTypes">
                                <div class="inner-card-type-row">
                                    <div class="card-type-title-piece">
                                        <div class="inner-card-type-title-piece">
                                            <h2 class="inner-card-type-title">{{ typeData.type }}</h2>
                                        </div>
                                    </div>

                                    <div class="row-of-cards">
                                        <div class="inner-row-of-cards">
                                            <div class="card" style="width: 18rem;" v-for="(gate, idx) in gateways[typeData.slug]">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ gate.name }}</h5>
                                                    <p class="card-text">{{ gate.desc }}</p>
                                                </div>

                                                <ul class="list-group list-group-flush" style="text-align: center;">
                                                    <li class="list-group-item">{{ gate.status[activeShop] }}</li>
                                                </ul>
                                                <div class="card-body" style="text-align: center;">
                                                    <a href="#" class="card-link" v-if="gate.assign[activeShop].type === 'button'" @click="toggleGatewayAssign(gateways[typeData.slug][idx], gate.assign[activeShop].action, typeData.slug)">{{ gate.assign[activeShop].markup }}</a>
                                                    <p class="card-link" v-if="gate.assign[activeShop].type === 'text'" v-html="gate.assign[activeShop].markup"></p>
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
import SexyHurricane from "../../widgets/loading/SexyHurricane";
export default {
    name: "PGMerchantManagerScreen",
    components: {
        SexyHurricane
    },
    props: ['shops', 'gateways', 'assignStatus'],
    watch: {
        assignStatus(status) {
            console.log('Watching assignStatus', status)
            if('error' in status) {
                this.loadingMsg = status.error;
                if(this.loading) {
                    this.loading = false;
                    let _this = this;
                    setTimeout(function() {
                        _this.loading = true;
                    }, 100)
                }
                else {
                    this.loading = true;
                }
            }
            else {

            }
        },
        activeShop(idx) {
            this.$emit('active-shop')
        },
        gateways(gateways) {
            console.log('Incoming gateways', gateways);
        }
    },
    data() {
        return {
            loadingMsg: 'Loading...',
            showShopGateways: false,
            loading: false,
            gatewayTypes: [
                {
                    type: 'Credit Card Gateways',
                    slug: 'credit'
                },
                {
                    type: 'Express Payment Gateways',
                    slug: 'express'
                },
                {
                    type: 'Installment Pay Gateways',
                    slug: 'install'
                }
            ],
            shopTableFields: ['Shop', 'Shop Type', 'CC', 'Express', 'Install'],
            activeShop: ''
        };
    },
    computed: {
        isDryRunEnabled() {
            let results = false;

            let enabled_credit = this.shops[this.activeShop]['curated_payment_gateways']['credit'];
            for(let x in enabled_credit) {

                // if gateway is not dry run, but shop has dry run enabled, disable
                if(enabled_credit[x]['payment_provider'].name === "Dry Run Test Gateway") {
                    console.log('Dry run is enabled.')
                    results = true;
                    break;
                }
            }

            return results;
        }
    },
    methods: {
        toggleGatewayAssign(gateway, action, slug) {
            console.log('About to trigger the gateway '+action+' call...', gateway);
            this.$emit('gateway-triggered', {
                action: action,
                gateway: gateway,
                merchantShop: this.activeShop,
                shopType: slug
            })

            let _this = this;
            $('.card-section').slideUp();
            let shopyName = this.shops[this.activeShop].name
            if(action === 'assign') {
                this.loadingMsg = `Assigning ${gateway.name} to ${shopyName}!`;
            }
            else if(action === 'un-assign') {
                this.loadingMsg = `UnAssigning ${gateway.name} from ${shopyName}... :(`;
            }

            this.loading = true;
        },
        reToggleMerchantPage() {
            $('.card-section').slideUp();
            this.loading = true;
            let _this = this;

            setTimeout(function() {
                _this.showShopGateways = false;
                _this.activeShop = '';
                $('.loading-section').slideUp(function () {
                    _this.loading = false;
                });

                $('.list-of-shops').slideDown(function () {
                    $('.list-description').slideDown();
                });
            }, 2000);

        },
        triggerShopGatewaySetup(idx) {
            this.activeShop = idx;
            $('.list-description').slideUp(function () {
                $('.list-of-shops').slideUp();
            });

            this.loading = true;
            let _this = this;

            setTimeout(function() {
                _this.loading = false;
                _this.showShopGateways = true;
            }, 1500);

        },
        renderShopTableCCField(cc_gates) {
            let results = 'None Assigned';

            if(cc_gates.length > 0) {
                results = cc_gates[0]['payment_provider']['name'];
            }

            return results;
        },
        renderShopTableExField(cc_gates) {
            let results = 'None Assigned';

            if(cc_gates.length === 1) {
                results = cc_gates[0]['payment_provider']['name'];
            }
            else if(cc_gates.length > 1) {
                results = 'Multiple Gateways';
            }

            return results;
        },
        renderShopTableInField(cc_gates) {
            let results = 'None Assigned';

            if(cc_gates.length > 0) {
                results = cc_gates[0]['payment_provider']['name'];
            }

            return results;
        },
    },
    mounted() {
        console.log('Gateways - ', this.gateways);
    }
}
</script>

<style scoped>
    @media screen {
        .card-announce {
            width: 100%;
        }

        .inner-card-announce {
            text-align: center;
        }

        .inner-card-type-title-piece {
            text-align: center;
        }
    }

    @media screen and (min-width: 999px) {
        .card-section {
            margin-top: 15%;
        }
    }

    @media screen and (min-width: 1000px) {
        .row-of-cards {
            width: 100%;
        }

        .inner-row-of-cards {
            display: flex;
            flex-flow: row-wrap;
        }

        .card-section {
            margin-top: 1em;
        }

        .inner-card-type-row {
            margin-top: 1.5em;
        }
    }
</style>
