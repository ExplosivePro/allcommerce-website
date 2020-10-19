<template>
    <div class="pg-client-manager">
        <div class="inner-pg-client-manager">
            <div class="reports-view" v-if="contentView === 'reports'">
                <div class="inner-reports-view">
                    <div class="derpor">
                        <h1> Reporting Goes Here. </h1>
                    </div>
                </div>
            </div>

            <div class="providers-view" v-else-if="contentView === 'providers'">
                <div class="inner-providers-view">
                    <div class="list-of-providers">
                        <div class="inner-list-of-providers">
                            <div class="description-segment">
                                <div class="inner-description-segment">
                                    <p><i>Payment Gateway Providers are a fundamental component of eCommerce and allows you
                                        to collect payments from the customer for purchasing your products! We have payment
                                        integrations to cover several ways to pay - Credit Card, Express Pay and Installment
                                        Pay! With a wide range of providers and adding more regularly, you can find and
                                        utilize a suite of payment providers that suite your shop(s)' needs!
                                    </i></p>
                                </div>
                            </div>
                            <div class="list-segment" v-if="providers.length > 0">
                                <div class="inner-list-segment">
                                    <h2> Credit Card Gateways</h2>
                                    <p><i> Standard Gateways allow the customer to enter their card info manually usually
                                        with the option to "save" their info for future purposes by utilizing a "rebill" token.</i></p>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col" v-for="(x, field) in providers[0]" v-if="(field !== 'disabled') && (field !== 'enabled')">{{ field }}</th>
                                            <th>More</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(provider, idx) in providers">
                                            <td v-for="(val, col) in provider" :scope="(col === 'title') ? 'row' : ''" v-if="(col !== 'disabled') && (col !== 'enabled') && (provider.type === 'Credit Card')">{{ val }}</td>
                                            <td v-if="(provider.type === 'Credit Card')"><button type="button"
                                                        class="btn "
                                                        :class="(provider.status === 'Enabled') ? 'btn-info' : 'btn-primary'"
                                                        @click="iClickedTheButton(provider.status, idx)" :disabled="provider.disabled">{{ (provider.status === 'Enabled') ? 'Manage' : 'Set Up' }}</button></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="list-segment" v-if="providers.length > 0">
                                <div class="inner-list-segment">
                                    <h2> Express Pay Gateways</h2>
                                    <p><i> These typically open in a pop up to complete the payment.</i></p>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col" v-for="(x, field) in providers[0]" v-if="field !== 'disabled'">{{ field }}</th>
                                            <th>More</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(provider, idx) in providers">
                                            <td v-for="(val, col) in provider" :scope="(col === 'title') ? 'row' : ''" v-if="(col !== 'disabled') && (provider.type === 'Express Pay')">{{ val }}</td>
                                            <td v-if="(provider.type === 'Express Pay')"><button type="button"
                                                                                                 class="btn "
                                                                                                 :class="(provider.status === 'Enabled') ? 'btn-info' : 'btn-primary'"
                                                                                                 @click="iClickedTheButton(provider.status, idx)" :disabled="provider.disabled">{{ (provider.status === 'Enabled') ? 'Manage' : 'Set Up' }}</button></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="list-segment" v-if="providers.length > 0">
                                <div class="inner-list-segment">
                                    <h2> Installment Pay Gateways</h2>
                                    <p><i> These gateways open up a new tab redirecting the user to the provider's page
                                        to accept loan terms before bringing the customer back to the payment screen to
                                        complete their purchase.</i></p>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col" v-for="(x, field) in providers[0]" v-if="field !== 'disabled'">{{ field }}</th>
                                            <th>More</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(provider, idx) in providers">
                                            <td v-for="(val, col) in provider" :scope="(col === 'title') ? 'row' : ''" v-if="(col !== 'disabled') && (provider.type === 'Installment Pay')">{{ val }}</td>
                                            <td v-if="(provider.type === 'Installment Pay')">
                                                <button type="button"
                                                        class="btn "
                                                        :class="(provider.status === 'Enabled') ? 'btn-info' : 'btn-primary'"
                                                        @click="iClickedTheButton(provider.status, idx)" :disabled="provider.disabled">{{ (provider.status === 'Enabled') ? 'Manage' : 'Set Up' }}
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="empty-segment" v-if="providers.length === 0">
                                <div class="inner-empty-segment">
                                    <h1> No Available Providers </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="active-provider-form" style="display: none;">
                        <div class="inner-active-provider-from">
                            <div class="go-back-btn">
                                <div class="inner-go-back-btn">
                                    <button type="button" class="btn btn-link" @click="iClickedTheButton('Go Back', 0)"><span><i class="fas fa-arrow-alt-left"></i></span> Go Back</button>
                                </div>
                            </div>

                            <div class="form-desc">
                                <div class="inner-form-desc">
                                    <p><i>{{ providerInfo.desc }}</i></p>
                                </div>
                            </div>

                            <div class="render-form" v-if="(providerInfo !== '') && (providerInfo.fields.length > 0)">
                                <div class="inner-render-form">
                                    <form>
                                        <div v-for="(field, idx) in providerInfo.fields" class="sweet-form-row form-group">
                                            <p style="margin:0;padding:0;"><label :for="field.name">{{ field.name }}</label></p>
                                            <input v-if="field.type === 'text'" type="text" :name="field.name" v-model="field.value"/>

                                            <p><small v-if="field.type === 'text'">{{ field.desc }}</small></p>
                                            <select v-if="field.type === 'select'" :name="field.name" class="form-control" v-model="field.value">
                                                <option value="">Select a {{ field.desc }}</option>
                                                <option v-for="(lbl, val) in field.options" :value="val">{{ lbl }}</option>
                                            </select>
                                        </div>
                                    </form>

                                    <button type="button" class="btn btn-success" @click="iClickedTheButton('Save Form', 0)"> Save </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="error-view" v-else>
                <div class="inner-error-view">
                    <div class="derpo">
                        <h1> Derp. </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PGClientManagerScreen",
    props: ['contentView', 'providers', 'providerInfo'],
    watch: {
        providerInfo(info) {
            console.log('Receiving Info ', info);
        }
    },
    methods: {
        iClickedTheButton(status, idx) {
            console.log(status);
            switch(status) {
                case 'Go Back':
                    $('.active-provider-form').slideUp();
                    setTimeout(function () {
                        $('.list-of-providers').slideDown();
                    }, 500)
                    break;

                case 'Enabled':
                case 'Not Set Up':
                    this.$emit('load-provider', idx)
                    $('.list-of-providers').slideUp();
                    setTimeout(function () {
                        $('.active-provider-form').slideDown();
                    }, 500)
                    break;

                case 'Save Form':
                    this.iClickedTheButton('Coming Soon', 0)
                    break;

                case 'Coming Soon!':
                case 'Coming Soon':
                    alert(status)
                    break;
            }
        }
    },
}
</script>

<style scoped>
@media screen {
    .providers-view {
        height: 100%;
        width: 100%;
    }

    .inner-providers-view {
        display: flex;
        flex-flow: column;
    }

    .list-of-providers {
        height: 100%;
        width: 100%;
    }

    .inner-list-of-providers {
        display: flex;
        flex-flow: column;
    }

    .description-segment, .list-segment {
        width: 100%;
    }
}

@media screen and (max-width: 999px) {
    .inner-providers-view {
        margin: 5% 0;
    }

    .inner-description-segment {
        margin: 2.5%;
    }

    .inner-description-segment p {
        font-size: 45%;
    }

    .inner-list-segment {
        margin: 0 1% 5%;
    }

    .inner-list-segment h2 {
        font-size: 1.25em;
        font-weight: 600;
        text-transform: uppercase;
    }

    .inner-render-form .form-control {
        width: 85%;
        margin: 0 auto;
        height: calc(0.6em + 0.75rem + 2px);
        padding: 0.275rem 0.75rem;
    }

    th, td, .inner-list-segment button {
        font-size: 50%;
    }

    @media screen and (max-width: 499px) {
        .inner-description-segment p {
            font-size: 25%;
        }

        th, td, .inner-list-segment button {
            font-size: 25%;
        }
    }
}

@media screen and (min-width: 1000px) {
    .inner-providers-view {
        margin: 2.5% 0;
    }

    .inner-description-segment {
        margin: 2.5%;
    }

    .inner-description-segment p {
        font-size: 65%;
    }

    .inner-list-segment {
        margin: 0 5% 2.5%;
    }

    .inner-list-segment h2 {
        font-size: 1.25em;
        font-weight: 600;
        text-transform: uppercase;
    }

    @media screen and (min-width: 1280px) {
        .inner-description-segment p {
            font-size: 85%;
        }
    }
}
</style>
