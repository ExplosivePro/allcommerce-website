<template>
    <div class="list-group list-group-accent">
        <div class="list-group-item list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase c-small">{{ title }}</div>

        <empty-context-tab v-if="tabComponent === 'empty-context-tab'"></empty-context-tab>
        <component v-bind:is="tabComponent" v-else></component>
    </div>
</template>

<script>

import EmptyContextTab from "./EmptyContextComponent";

export default {
    name: "ContextTabComponent",
    components: {
        EmptyContextTab
    },
    props: ['title', 'tabComponent'],
}
</script>

<style scoped>

</style>
