<template>
    <div class="default-left-container">
        <div class="inner-default-left">
            <polaris-card
                title="Welcome to your dashboard!"
                :primary-footer-action="{content: 'Logout', onAction: testAlert}"
                :actions="[]">
                <polaris-card-section>
                    <p>Updates to your account will appear here.</p>
                </polaris-card-section>
            </polaris-card>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import PolarisVue from '@eastsideco/polaris-vue';
    import '@eastsideco/polaris-vue/lib/polaris-vue.css';

    // Register the plugin with Vue
    Vue.use(PolarisVue);

    export default {
        name: "defaultLeft",
        methods: {
            testAlert() {
                window.location.href = '/logout'
            }
        }
    }
</script>

<style>
    html,
    body {
        font-size: inherit;
        font-weight: inherit;
        line-height: inherit;
        text-transform: inherit;
        letter-spacing: inherit;
        font-weight: inherit;
        color: inherit;
        font-family: "Nunito", sans-serif;
    }
    @media (min-width: 40em) {
        html,
        body {
            font-size: inherit; } }

    html {
        position: inherit;
        box-sizing: inherit;
        font-size: inherit;
        -webkit-font-smoothing: inherit;
        -moz-osx-font-smoothing: inherit;
        text-size-adjust: inherit;
        text-rendering: inherit; }

    body {
        min-height: inherit;
        margin: inherit;
        padding: inherit;
        background-color: inherit }

    * {
        box-sizing: inherit; }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
        margin: inherit;
        font-size: inherit;
        font-weight: inherit;
    }

    h1 {
        font-size: 2.25rem !important;
    }

    .Polaris-Card__Section p {
        margin-bottom: 0.75em;
    }

    .default-right-container h1,
    .default-right-container h2,
    .default-right-container h3,
    .default-right-container h4,
    .default-right-container h5,
    .default-right-container h6,
    .default-right-container p {
        margin: 0;
        font-size: 1em;
        font-weight: 400; }

    .default-right-container a:not([data-polaris-unstyled]) {
        color: #007ace;
        text-decoration: none; }
    .default-right-container a:not([data-polaris-unstyled]):hover, a:not([data-polaris-unstyled]):focus, a:not([data-polaris-unstyled]):active {
        outline: none;
        color: #084e8a; }

    .c-dark-theme .Polaris-Card {
        background-color: #23242d;
    }

    .Polaris-Button__Content {
        font-size: 1.0rem;
    }

    .Polaris-Button {
        min-height: 1rem;
        height: 1rem;
    }

    .Polaris-Card__Section {
        padding: 0.5rem 2rem;
    }

    .Polaris-Heading {
        font-size: 1.3rem;
    }
</style>
