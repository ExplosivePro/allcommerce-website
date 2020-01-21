<template>
    <div class="leadbinder-footer-container">
        <h1> Dope, I'm a Footer.</h1>
        <div id="copyright-segment">
            <p id="copy-right"><i class="fad fa-copyright"></i> 2020 Cape & Bay. All Rights Reserved.</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FooterComponent"
    }
</script>

<style scoped>
    @media screen {
        .leadbinder-footer-container {
            color: #fff;
            text-align: center;
        }
    }

    @media screen and (max-width: 999px) {
        .leadbinder-footer-container {
            padding: 0.25em;
        }
    }

    @media screen and (min-width: 1000px) {
        .leadbinder-footer-container {
            padding: 0.25em;
        }
    }
</style>
