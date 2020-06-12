<template>
    <component v-bind:is="componentName"></component>
</template>

<script>
    export default {
        name: "shopifyErrorContainer",
        props: ['errorMsg', 'componentName']
    }
</script>

<style scoped>

</style>
