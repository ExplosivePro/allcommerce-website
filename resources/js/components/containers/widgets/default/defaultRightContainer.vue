<template>
    <widget></widget>
</template>

<script>
    import Widget from '../../../presenters/widgets/default/defaultRight'
    export default {
        name: "defaultRightContainer",
        components: {
            Widget
        },
        props: ['client_id']
    }
</script>

<style scoped>

</style>
